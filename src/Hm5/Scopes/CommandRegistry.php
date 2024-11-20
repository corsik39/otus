<?php

namespace App\Hm5\Scopes;

use App\Hm2\Command\ICommand;

class CommandRegistry
{
	private static array $commands = [];

	public static function register($key, callable $commandFactory): void
	{
		self::$commands[$key] = $commandFactory;
	}

	public static function get($key, ...$args): ICommand
	{
		if (!self::has($key))
		{
			throw new \Exception("Command {$key} not found.");
		}

		$command = call_user_func(self::$commands[$key], ...$args);

		if (!$command instanceof ICommand)
		{
			throw new \Exception("Command for key {$key} is not a valid ICommand instance.");
		}

		return $command;
	}

	public static function has(string $key): bool
	{
		return isset(self::$commands[$key]);
	}
}
