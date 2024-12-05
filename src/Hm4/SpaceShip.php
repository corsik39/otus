<?php

namespace App\Hm4;

use App\Hm2\Command\ICommand;

class SpaceShip implements ICommand
{
	private array $commands;

	public function __construct(...$commands)
	{
		$this->commands = $commands;
	}

	public function execute(): void
	{
		foreach ($this->commands as $command)
		{
			$command->execute();
		}
	}
}
