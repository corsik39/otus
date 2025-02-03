<?php

namespace App\Hm3\Command\Queue;

use App\Hm2\Command\ICommand;
use App\Hm5\Ioc;

class CommandQueue
{
	private static array $instances = [];
	protected bool $softStopping = false;
	protected bool $running = false;
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

	protected function runQueue(): void
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
