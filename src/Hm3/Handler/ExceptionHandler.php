<?php

namespace App\Hm3\Handler;

use App\Hm2\Command\ICommand;
use App\Hm3\Command\LogCommand;
use Exception;

class ExceptionHandler
{
	private static array $handlersIoc = [];

	public static function register($commandType, $exceptionType, callable $handler)
	{
		self::$handlers[$commandType][$exceptionType] = $handler;
	}

	public static function handle(ICommand $command, Exception $exception)
	{
		$commandType = get_class($command);
		$exceptionType = get_class($exception);

		if (isset(self::$handlers[$commandType][$exceptionType]))
		{
			return call_user_func(self::$handlers[$commandType][$exceptionType], $command, $exception);
		}

		return new LogCommand($exception);
	}
}
