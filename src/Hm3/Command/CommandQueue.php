<?php

namespace App\Hm3\Command;

use App\Hm2\Command\ICommand;

class CommandQueue
{
	private static array $instances = [];
	private array $queue = [];

	public static function getInstance(): CommandQueue
	{
		$self = static::class;
		if (!isset(self::$instances[$self]))
		{
			self::$instances[$self] = new static();
		}

		return self::$instances[$self];
	}

	public function enqueue(ICommand $command): void
	{
		$this->queue[] = $command;
	}

	public function dequeue(): ?ICommand
	{
		return array_shift($this->queue);
	}

	public function isEmpty(): bool
	{
		return empty($this->queue);
	}

	public function getQueue(): array
	{
		return $this->queue;
	}
}
