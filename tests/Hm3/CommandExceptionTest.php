<?php

namespace App\Tests\Hm3;

use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\GameObject;
use App\Hm2\IocBattle;
use App\Hm2\Vector;
use App\Hm3\Command\LogCommand;
use App\Hm3\Command\RetryCommand;
use App\Hm3\IocException;
use App\Hm3\Logs;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CommandExceptionTest extends TestCase
{
	public function testLogCommand(): void
	{
		try
		{
			Ioc::resolve('MoveCommand', $this->movingAdapter)->execute();
		}
		catch (\Exception $exception)
		{
			Ioc::resolve('LogCommand', $exception)->execute();;
			$this->assertContains("Unable to read location.", Logs::getInstance()->getLogs());
		}
	}

	public function testCommandQueueLog(): void
	{
		try
		{
			Ioc::resolve('MoveCommand', $this->movingAdapter)->execute();
			$this->movingAdapter->getLocation();
		}
		catch (\Exception $exception)
		{
			$queue = Ioc::resolve('CommandQueueException.add', Ioc::resolve('LogCommand', $exception));

			$this->assertFalse($queue->isEmpty());
			$this->assertInstanceOf(LogCommand::class, $queue->dequeue());
		}
	}

	public function testRetryCommand(): void
	{
		try
		{
			Ioc::resolve('MoveCommand', $this->movingAdapter)->execute();
		}
		catch (\RuntimeException $exception)
		{
			$this->expectException(RuntimeException::class);
			$this->expectExceptionMessage("Unable to read location.");

			Ioc::resolve('RetryCommand', Ioc::resolve('MoveCommand', $this->movingAdapter))->execute();
		}
	}

	public function testAddQueueRetryCommand(): void
	{
		try
		{
			Ioc::resolve('MoveCommand', $this->movingAdapter)->execute();
		}
		catch (\RuntimeException $exception)
		{
			$queue = Ioc::resolve('ExceptionHandler.addRetry', Ioc::resolve('MoveCommand', $this->movingAdapter));
			$this->assertFalse($queue->isEmpty());
			$retryCommand = $queue->dequeue();
			$this->assertInstanceOf(RetryCommand::class, $retryCommand);

			$this->expectException(RuntimeException::class);
			$this->expectExceptionMessage("Unable to read location.");
			$retryCommand->execute();
		}
	}

	public function testExceptionHandleRetryAndLog(): void
	{
		$moveCommand = Ioc::resolve('MoveCommand', $this->movingAdapter);

		try
		{
			$moveCommand->execute();
		}
		catch (\RuntimeException $exception)
		{
			Ioc::resolve('ExceptionHandler.handleRetryAndLog', $moveCommand, $exception, true);

			$logs = Logs::getInstance();
			$this->assertFalse($logs->isEmpty());
			$this->assertContains("Unable to read location.", Logs::getInstance()->getLogs());
		}
	}

	protected function setUp(): void
	{
		IocInit::registry();
		IocBattle::registry();
		IocException::registry();

		$gameObject = new GameObject();
		$gameObject->setProperty('velocity', new Vector(-7, 3));

		$this->movingAdapter = new MovingObjectAdapter($gameObject);
	}
}
