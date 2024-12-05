<?php

namespace App\Tests\Hm4;

use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\Adapter\RotatingObjectAdapter;
use App\Hm2\Angle;
use App\Hm2\GameObject;
use App\Hm2\IocBattle;
use App\Hm2\Vector;
use App\Hm4\BurnStrategy;
use App\Hm4\FuelTank;
use App\Hm4\IocBurn;
use App\Hm4\SpaceShip;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CommandTest extends TestCase
{
	public function testBurnFuelCommand(): void
	{
		Ioc::resolve('BurnFuelCommand', $this->fuelTank)->execute();
		$this->assertEquals(9, $this->fuelTank->getFuelLevel());

		$this->expectExceptionMessage("There is not enough fuel.");
		Ioc::resolve('BurnFuelCommand', $this->emptyTank)->execute();
	}

	public function testCheckFuelCommand(): void
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("There is not enough fuel.");
		Ioc::resolve('CheckFuelCommand', $this->fuelTank, 20)->execute();
	}

	public function testCommandMoveStraightLine(): void
	{
		$spaceShip = new SpaceShip(
			Ioc::resolve('CheckFuelCommand', $this->fuelTank),
			Ioc::resolve('MoveCommand', $this->movingAdapter),
			Ioc::resolve('BurnFuelCommand', $this->fuelTank),
		);

		$spaceShip->execute();
		$newLocation = $this->movingAdapter->getLocation();

		$this->assertEquals(9, $this->fuelTank->getFuelLevel());
		$this->assertEquals([5, 8], [$newLocation->getX(), $newLocation->getY()]);
	}

	public function testCommandRotate(): void
	{
		$spaceShip = new SpaceShip(
			Ioc::resolve('CheckFuelCommand', $this->fuelTank),
			Ioc::resolve('RotateCommand', $this->rotateAdapter),
			Ioc::resolve('BurnFuelCommand', $this->fuelTank),
		);

		$spaceShip->execute();

		$newAngle = $this->rotateAdapter->getAngle()->getAngle();
		$this->assertEquals(9, $this->fuelTank->getFuelLevel());
		$this->assertEquals(10, $newAngle);
	}

	protected function setUp(): void
	{
		IocInit::registry();
		IocBattle::registry();
		IocBurn::registry();

		$gameObject = new GameObject();
		$gameObject->setProperty('location', new Vector(12, 5));
		$gameObject->setProperty('velocity', new Vector(-7, 3));
		$gameObject->setProperty('angle', new Angle(350, 360));
		$gameObject->setProperty('angularVelocity', new Angle(20, 360));

		$this->rotateAdapter = new RotatingObjectAdapter($gameObject);
		$this->movingAdapter = new MovingObjectAdapter($gameObject);
		$this->fuelTank = new FuelTank(10, new BurnStrategy(1));

		$this->emptyTank = new FuelTank(1, new BurnStrategy(2));
	}
}
