<?php

namespace App\Tests\Hm2;

use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\Command\MoveCommand;
use App\Hm2\Vector;
use App\Mock\GameObjectMock;
use Exception;
use PHPUnit\Framework\TestCase;

class MoveCommandTest extends TestCase
{
	public function testMoveCommand(): void
	{
		$gameObject = new GameObjectMock();
		$gameObject->setProperty('location', new Vector(12, 5));
		$gameObject->setProperty('velocity', new Vector(-7, 3)); // Correct velocity vector

		$adapter = new MovingObjectAdapter($gameObject);
		$moveCommand = new MoveCommand($adapter);
		$moveCommand->execute();

		$newLocation = $adapter->getLocation();
		$this->assertEquals([5, 8], [$newLocation->getX(), $newLocation->getY()]);
	}

	public function testMoveCommandWithInvalidLocation(): void
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Unable to read location.");

		$gameObject = new GameObjectMock();
		$gameObject->setProperty('velocity', new Vector(-7, 3));

		$adapter = new MovingObjectAdapter($gameObject);
		$moveCommand = new MoveCommand($adapter);
		$moveCommand->execute();
	}

	public function testMoveCommandWithInvalidVelocity(): void
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Unable to read velocity.");

		$gameObject = new GameObjectMock();
		$gameObject->setProperty('location', new Vector(12, 5));

		$adapter = new MovingObjectAdapter($gameObject);
		$moveCommand = new MoveCommand($adapter);
		$moveCommand->execute();
	}

	public function testMoveCommandWithUnmodifiableLocation(): void
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("Cannot modify location.");

		$gameObject = new class extends GameObjectMock {
			public function setProperty(string $string, $value): void
			{
				if ($string === 'location')
				{
					throw new \RuntimeException("Cannot modify location.");
				}
				parent::setProperty($string, $value);
			}
		};

		$gameObject->setProperty('location', new Vector(12, 5));
		$gameObject->setProperty('velocity', new Vector(-7, 3));

		$adapter = new MovingObjectAdapter($gameObject);
		$moveCommand = new MoveCommand($adapter);
		$moveCommand->execute();
	}
}
