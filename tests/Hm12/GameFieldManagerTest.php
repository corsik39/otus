<?php

namespace App\Tests\Hm12;

use App\Hm12\GameFieldManager;
use App\Hm2\GameObject;
use App\Hm2\IocBattle;
use App\Hm2\Vector;
use App\Hm3\IocException;
use App\Hm3\Logs;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use App\Hm8\IocGameController;
use PHPUnit\Framework\TestCase;

class GameFieldManagerTest extends TestCase
{
	private GameFieldManager $gameFieldManager;
	private GameObject $gameObject;

	public function testObjectMovedOutsideField(): void
	{
		$this->gameObject->setProperty('location', new Vector(20, 20));
		$this->gameObject->setProperty('velocity', new Vector(5, 5));
		$this->gameFieldManager->registerObject($this->gameObject);

		$initialNeighborhood = $this->gameFieldManager->getObjectNeighborhood($this->gameObject);

		$queue = Ioc::resolve('CommandQueue');
		for ($i = 0; $i < 5; $i++)
		{
			Ioc::resolve('CommandQueue.add', Ioc::resolve('Move', $this->gameObject));
		}
		Ioc::resolve('StartCommand', $queue);

		$finalNeighborhood = $this->gameFieldManager->getObjectNeighborhood($this->gameObject);

		if ($finalNeighborhood === null)
		{
			$this->fail("Object moved out of bounds, test scenario needs adjustment.");
		}
		else
		{
			$this->assertNotSame($initialNeighborhood, $finalNeighborhood, "Object did not move to a new neighborhood.");
		}
	}

	public function testObjectMovesWithinBounds(): void
	{
		$this->gameObject->setProperty('location', new Vector(30, 30));
		$this->gameObject->setProperty('velocity', new Vector(5, 5));
		$this->gameFieldManager->registerObject($this->gameObject);

		$queue = Ioc::resolve('CommandQueue');
		for ($i = 0; $i < 5; $i++)
		{
			Ioc::resolve('CommandQueue.add', Ioc::resolve('Move', $this->gameObject));
		}
		Ioc::resolve('StartCommand', $queue);

		$finalLocation = $this->gameObject->getProperty('location');

		$this->assertGreaterThanOrEqual(0, $finalLocation->getX(), "Object moved out of bounds on X-axis.");
		$this->assertLessThanOrEqual(100, $finalLocation->getX(), "Object moved out of bounds on X-axis.");
		$this->assertGreaterThanOrEqual(0, $finalLocation->getY(), "Object moved out of bounds on Y-axis.");
		$this->assertLessThanOrEqual(100, $finalLocation->getY(), "Object moved out of bounds on Y-axis.");
	}

	public function testObjectMovesInDifferentDirections(): void
	{
		$this->gameObject->setProperty('location', new Vector(50, 50));
		$this->gameObject->setProperty('velocity', new Vector(5, -5));
		$this->gameFieldManager->registerObject($this->gameObject);

		$queue = Ioc::resolve('CommandQueue');
		Ioc::resolve('CommandQueue.add', Ioc::resolve('Move', $this->gameObject));
		Ioc::resolve('StartCommand', $queue);

		$finalLocation = $this->gameObject->getProperty('location');
		$expectedLocation = new Vector(55, 45);

		$this->assertEquals($expectedLocation, $finalLocation, "Object did not move correctly.");
	}

	public function testObjectsCollision(): void
	{
		$this->gameObject->setProperty('location', new Vector(20, 20));
		$this->gameObject->setProperty('velocity', new Vector(5, 0));
		$this->gameFieldManager->registerObject($this->gameObject);

		$this->gameObject2->setProperty('location', new Vector(30, 20));
		$this->gameObject2->setProperty('velocity', new Vector(-5, 0));
		$this->gameFieldManager->registerObject($this->gameObject2);

		$queue = Ioc::resolve('CommandQueue');
		for ($i = 0; $i < 1; $i++)
		{
			Ioc::resolve('CommandQueue.add', Ioc::resolve('Move', $this->gameObject));
			Ioc::resolve('CommandQueue.add', Ioc::resolve('Move', $this->gameObject2));
		}

		Ioc::resolve('StartCommand', $queue);

		$logException = Ioc::resolve('CommandQueueException')->dequeue()->execute();
		$this->assertStringContainsString('Exception: Collision detected at location', Logs::getInstance()->getLogs()[0]);
	}

	protected function setUp(): void
	{
		IocInit::registry();
		IocBattle::registry();
		IocException::registry();
		IocGameController::registry();

		$this->gameFieldManager = new GameFieldManager(100, 100, 10);
		$this->gameObject = new GameObject();
		$this->gameObject2 = new GameObject();

		$this->gameObject->setGameFieldManager($this->gameFieldManager);
		$this->gameObject2->setGameFieldManager($this->gameFieldManager);
	}
}
