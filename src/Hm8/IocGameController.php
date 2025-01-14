<?php

namespace App\Hm8;

use App\Hm5\interface\IIocRegistry;
use App\Hm5\Ioc;
use App\Hm7\StartCommand;
use App\Hm7\StopHardCommand;
use App\Hm7\StopSoftCommand;

class IocGameController implements IIocRegistry
{
	public static function registry(): void
	{
		Ioc::resolve('IoC.Register', 'InterpretCommand', static function (IncomingMessageDto $incomingData) {
			return new InterpretCommand($incomingData);
		})->execute();

		Ioc::resolve('IoC.Register', 'IncomingMessage', static function (string $jsonData) {
			return new IncomingMessageDto($jsonData);
		})->execute();

		Ioc::resolve('IoC.Register', 'ReceiveMessage', static function ($jsonData) {
			return (new GameController())->receiveMessage($jsonData);
		})->execute();

		Ioc::resolve('IoC.Register', 'StartCommand', static function ($queue) {
			(new StartCommand($queue))->execute();
		})->execute();

		Ioc::resolve('IoC.Register', 'StopSoftCommand', static function ($queue) {
			(new StopSoftCommand($queue))->execute();
		})->execute();

		Ioc::resolve('IoC.Register', 'StopHardCommand', static function ($queue) {
			(new StopHardCommand($queue))->execute();
		})->execute();
	}
}
