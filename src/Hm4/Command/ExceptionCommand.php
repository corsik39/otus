<?php

namespace App\Hm4\Command;

use App\Hm2\Command\ICommand;

class ExceptionCommand implements ICommand
{
	private string $exceptionMessage;

	public function __construct(string $exceptionMessage)
	{
		$this->exceptionMessage = $exceptionMessage;
	}

	public function execute(): void
	{
		throw new \RuntimeException($this->exceptionMessage);
	}
}
