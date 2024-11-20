<?php

namespace App\Tests\Hm3;

use App\Hm2\Command\ICommand;
use Exception;
use App\Hm3\Command\LogCommand;
use PHPUnit\Framework\TestCase;
use App\Hm3\Command\RetryCommand;

class CommandTest extends TestCase
{
	public function testLogCommand()
	{
		$exception = new Exception("Test exception");
		$logCommand = new LogCommand($exception);

		// Перехватываем вывод в лог
		$this->expectOutputString("Test exception\n");
		$logCommand->execute();
	}

	public function testRetryCommand()
	{
		$mockCommand = $this->createMock(ICommand::class);
		$mockCommand->expects($this->exactly(2))
			->method('execute')
			->will($this->onConsecutiveCalls(
				$this->throwException(new Exception("Test exception")),
				$this->returnValue(null)
			))
		;

		$retryCommand = new RetryCommand($mockCommand, 2);
		$retryCommand->execute();
	}
}
