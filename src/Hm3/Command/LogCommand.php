<?php

namespace App\Hm3\Command;

use App\Hm2\Command\ICommand;
use Exception;

class LogCommand implements ICommand
{
	private $exception;

	public function __construct(Exception $exception)
	{
		$this->exception = $exception;
	}

	public function execute(): void
	{
		// Записываем информацию об исключении в лог
		error_log($this->exception->getMessage());
	}
}
