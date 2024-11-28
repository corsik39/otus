<?php

namespace App\Hm3\Command;

use App\Hm2\Command\ICommand;

class RetryCommand implements ICommand
{
	private ICommand $command;

	public function __construct(ICommand $command)
	{
		$this->command = $command;
	}

	public function execute(): void
	{
		$this->command->execute();
	}
}
