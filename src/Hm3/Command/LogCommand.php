<?php

namespace App\Hm3\Command;

use App\Hm2\Command\ICommand;
use App\Hm3\Logs;

class LogCommand implements ICommand
{
	private array $logs;
	private \Exception $exception;

	public function __construct(\Exception $exception)
	{
		$this->exception = $exception;
	}

	public function execute(): void
	{
		Logs::getInstance()->add($this->exception->getMessage());
	}
}
