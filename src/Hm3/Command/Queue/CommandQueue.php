<?php

namespace App\Hm3\Command\Queue;

use App\Hm2\Command\ICommand;
use App\Hm5\Ioc;

class CommandQueue
{
	private static array $instances = [];
	private array $queue = [];
	private bool $running = false;
	private bool $softStopping = false;

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
		// Если добавлена новая команда, отменяем мягкую остановку
		$this->softStopping = false;
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

	public function start(): void
	{
		$this->running = true;
		$this->runQueue();
	}

	public function stopHard(): void
	{
		$this->running = false;
		$this->softStopping = false;
	}

	public function stopSoft(): void
	{
		$this->softStopping = true;
	}

	public function isRunning(): bool
	{
		return $this->running;
	}

	private function runQueue(): void
	{
		while ($this->running)
		{
			while (!$this->isEmpty())
			{
				$command = $this->dequeue();
				if ($command)
				{
					try
					{
						$command->execute();
					}
					catch (\Exception $exception)
					{
						Ioc::resolve('CommandQueueException.add', Ioc::resolve('LogCommand', $exception));
					}
				}
			}

			if ($this->softStopping)
			{
				if ($this->isEmpty())
				{
					$this->running = false;
				}
			}
			else
			{
				$this->running = false;
			}
		}
	}
}
