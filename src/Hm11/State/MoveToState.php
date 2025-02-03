<?php

namespace App\Hm11\State;

use App\Hm11\Command\RunCommand;
use App\Hm11\StatefulCommandQueue;
use App\Hm2\Command\ICommand;
use App\Hm7\StopHardCommand;

class MoveToState implements StateInterface
{
	private StatefulCommandQueue $targetQueue;

	public function __construct(StatefulCommandQueue $targetQueue)
	{
		$this->targetQueue = $targetQueue;
	}

	public function handle(StatefulCommandQueue $queue, ICommand $command): ?StateInterface
	{
		if ($command instanceof StopHardCommand)
		{
			$command->execute();

			return null;
		}

		if ($command instanceof RunCommand)
		{
			return new NormalState();
		}

		// Перенаправление команды в другую очередь
		$this->targetQueue->enqueue($command);

		return $this; // Остаемся в текущем состоянии
	}
}
