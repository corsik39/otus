<?php

namespace App\Tests\Hm2;

use App\Hm2\Adapter\RotatingObjectAdapter;
use App\Hm2\Angle;
use App\Hm2\Command\RotateCommand;
use App\Hm2\GameObject;
use PHPUnit\Framework\TestCase;

class RotateCommandTest extends TestCase
{
	public function testRotateCommand(): void
	{
		$gameObject = new GameObject();
		$gameObject->setProperty('angle', new Angle(0, 360));
		$gameObject->setProperty('angularVelocity', new Angle(90, 360));

		$adapter = new RotatingObjectAdapter($gameObject);
		$rotateCommand = new RotateCommand($adapter);

		$rotateCommand->execute();

		$newAngle = $adapter->getAngle()->getAngle();
		$this->assertEquals(90, $newAngle);

		$rotateCommand->execute();
		$newAngle = $adapter->getAngle()->getAngle();
		$this->assertEquals(180, $newAngle);
	}

	public function testRotateCommandWithFullCircle(): void
	{
		$gameObject = new GameObject();
		$gameObject->setProperty('angle', new Angle(350, 360));
		$gameObject->setProperty('angularVelocity', new Angle(20, 360));

		$adapter = new RotatingObjectAdapter($gameObject);
		$rotateCommand = new RotateCommand($adapter);

		$rotateCommand->execute();

		$newAngle = $adapter->getAngle()->getAngle();
		$this->assertEquals(10, $newAngle);
	}
}
