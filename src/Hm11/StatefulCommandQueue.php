<?php

namespace App\Hm11;

use App\Hm11\State\NormalState;
use App\Hm11\State\StateInterface;
use App\Hm3\Command\Queue\CommandQueue;

class StatefulCommandQueue extends CommandQueue
{
	private ?StateInterface $state;

	public function __construct()
	{
		$this->state = new NormalState();
	}

	public function getCurrentState(): ?StateInterface
	{
		return $this->state;
	}

	protected function runQueue(): void
	{
		while ($this->isRunning())
		{
			while (!$this->isEmpty())
			{
				$command = $this->dequeue();
				if ($command)
				{
					$this->state = $this->state->handle($this, $command);
					if ($this->state === null)
					{
						$this->stopHard();
						break;
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
