<?php

namespace App\Hm3;

use App\Hm2\Command\ICommand;
use App\Hm3\Command\LogCommand;
use App\Hm3\Command\Queue\CommandQueue;
use App\Hm3\Command\Queue\CommandQueueException;
use App\Hm3\Command\RetryCommand;
use App\Hm3\Handler\ExceptionHandler;
use App\Hm5\interface\IIocRegistry;
use App\Hm5\Ioc;

class IocException implements IIocRegistry
{
	public static function registry(): void
	{
		Ioc::resolve('IoC.Register', 'LogCommand', static function ($exception) {
			return new LogCommand($exception);
		})->execute();

		Ioc::resolve('IoC.Register', 'CommandQueue', static function () {
			return CommandQueue::getInstance();
		})->execute();

		Ioc::resolve('IoC.Register', 'CommandQueueException', static function () {
			return CommandQueueException::getInstance();
		})->execute();

		Ioc::resolve('IoC.Register', 'CommandQueue.add', static function (ICommand $command) {
			$queue = CommandQueue::getInstance();
			$queue->enqueue($command);

			return $queue;
		})->execute();

		Ioc::resolve('IoC.Register', 'CommandQueueException', static function () {
			return CommandQueueException::getInstance();
		})->execute();

		Ioc::resolve('IoC.Register', 'CommandQueueException.add', static function (ICommand $command) {
			$queue = CommandQueueException::getInstance();
			$queue->enqueue($command);

			return $queue;
		})->execute();

		Ioc::resolve('IoC.Register', 'RetryCommand', static function (ICommand $command) {
			return new RetryCommand($command);
		})->execute();

		Ioc::resolve('IoC.Register', 'ExceptionHandler.addRetry', static function (ICommand $command) {
			$queue = Ioc::resolve('CommandQueue');
			$queue->enqueue(new RetryCommand($command));

			return $queue;
		})->execute();

		Ioc::resolve('IoC.Register', 'ExceptionHandler.handleRetryAndLog', static function (...$params) {
			(new ExceptionHandler())->handleRetryAndLog(...$params);
		})->execute();
	}
}
