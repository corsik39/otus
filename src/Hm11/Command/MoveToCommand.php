<?php

namespace App\Hm11\Command;

use App\Hm11\StatefulCommandQueue;
use App\Hm2\Command\ICommand;

class MoveToCommand implements ICommand
{
	private StatefulCommandQueue $targetQueue;

	public function __construct(StatefulCommandQueue $targetQueue)
	{
		$this->targetQueue = $targetQueue;
	}

	public function execute(): void
	{
	}

	public function getTargetQueue(): StatefulCommandQueue
	{
		return $this->targetQueue;
	}
}
