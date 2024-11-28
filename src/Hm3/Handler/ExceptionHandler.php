<?php

namespace App\Hm3\Handler;

use App\Hm2\Command\ICommand;
use App\Hm3\Command\LogCommand;
use App\Hm3\Command\RetryCommand;
use App\Hm5\Ioc;

class ExceptionHandler
{
	public function handleRetryAndLog(ICommand $command, \Exception $exception, bool $retry = false): void
	{
		if ($retry)
		{
			try
			{
				(new RetryCommand($command))->execute();
			}
			catch (\RuntimeException $exception)
			{
				Ioc::resolve('ExceptionHandler.handleRetryAndLog', $command, $exception, false);
			}
		}
		else
		{
			(new LogCommand($exception))->execute();
		}
	}
}
