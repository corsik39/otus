<?php

namespace App\Hm7;

use App\Hm2\Command\ICommand;
use App\Hm3\Command\Queue\CommandQueue;

class StopSoftCommand implements ICommand
{
	private CommandQueue $queue;

	public function __construct(CommandQueue $queue)
	{
		$this->queue = $queue;
	}

	public function execute(): void
	{
		$this->queue->stopSoft();
	}
}
