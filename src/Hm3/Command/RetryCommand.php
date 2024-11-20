<?php

namespace App\Hm3\Command;

use App\Hm2\Command\ICommand;

class RetryCommand implements ICommand
{
	private $originalCommand;
	private $retryCount;

	public function __construct(ICommand $originalCommand, $retryCount = 1)
	{
		$this->originalCommand = $originalCommand;
		$this->retryCount = $retryCount;
	}

	public function execute(): void
	{
		if ($this->retryCount > 0)
		{
			try
			{
				$this->originalCommand->execute();
			}
			catch (Exception $e)
			{
				$this->retryCount--;
				throw $e;
			}
		}
		else
		{
			throw new Exception("Retry limit exceeded");
		}
	}
}
