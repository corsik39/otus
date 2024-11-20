<?php

namespace App\Tests\Hm3;

use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\Command\MoveCommand;
use App\Hm2\Vector;
use App\Hm3\Handler\ExceptionHandler;
use App\Mock\GameObjectMock;
use Exception;
use PHPUnit\Framework\TestCase;

class ExceptionHandlerTest extends TestCase
{
	public function testMoveCommandWithInvalidLocation(): void
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Unable to read location.");

		$gameObject = new GameObjectMock();
		$gameObject->setProperty('velocity', new Vector(-7, 3));

		$adapter = new MovingObjectAdapter($gameObject);
		$moveCommand = new MoveCommand($adapter);

		try
		{
			$moveCommand->execute();
		}
		catch (exception $exception)
		{
			ExceptionHandler::handle($moveCommand, $exception);
		}

	}
}
