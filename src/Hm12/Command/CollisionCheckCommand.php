<?php

namespace App\Hm12\Command;

use App\Hm12\GameFieldManager;
use App\Hm2\Command\ICommand;
use App\Hm2\Interface\IGameObject;
use App\Hm2\Vector;

class CollisionCheckCommand implements ICommand
{
	private IGameObject $gameObject;
	private GameFieldManager $gameFieldManager;
	private Vector $newLocation;

	public function __construct(IGameObject $gameObject, GameFieldManager $gameFieldManager, Vector $newLocation)
	{
		$this->gameObject = $gameObject;
		$this->gameFieldManager = $gameFieldManager;
		$this->newLocation = $newLocation;
	}

	public function execute(): void
	{
		$neighborhood = $this->gameFieldManager->determineNeighborhood($this->newLocation);
		if ($this->checkCollision($neighborhood))
		{
			throw new \Exception("Collision detected at location");
		}
	}

	private function checkCollision($neighborhood): bool
	{
		foreach ($neighborhood->getObjects() as $existingObject)
		{
			if ($existingObject !== $this->gameObject)
			{
				$existingLocation = $existingObject->getProperty('location');
				if ($this->locationsAreEqual($existingLocation, $this->newLocation))
				{
					return true;
				}
			}
		}

		return false;
	}

	private function locationsAreEqual(Vector $location1, Vector $location2): bool
	{
		return $location1->getX() === $location2->getX() && $location1->getY() === $location2->getY();
	}
}
