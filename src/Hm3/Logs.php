<?php

namespace App\Hm3;

class Logs
{
	private static array $instances = [];
	private array $logs = [];

	public static function getInstance(): Logs
	{
		$self = static::class;
		if (!isset(self::$instances[$self]))
		{
			self::$instances[$self] = new static();
		}

		return self::$instances[$self];
	}

	public function add(string $message): void
	{
		$this->logs[] = $message;
	}

	public function clear(string $message): void
	{
		$this->logs[] = $message;
	}

	public function isEmpty(): bool
	{
		return empty($this->logs);
	}

	public function has(string $message): bool
	{
		return in_array($message, $this->logs, true);
	}

	public function getLogs(): array
	{
		return $this->logs;
	}
}
