<?php

namespace App\Hm12;

use App\Hm12\Command\CollisionCheckCommand;
use App\Hm2\Interface\IGameObject;
use App\Hm2\Vector;

class GameFieldManager
{
	private array $neighborhoods = [];
	private int $neighborhoodSize;

	public function __construct(int $width, int $height, int $neighborhoodSize)
	{
		$this->neighborhoodSize = $neighborhoodSize;
		$this->initializeNeighborhoods($width, $height);
	}

	public function registerObject(IGameObject $object): void
	{
		$location = $object->getProperty('location');
		if ($location instanceof Vector)
		{
			$collisionCheckCommand = new CollisionCheckCommand($object, $this, $location);
			$collisionCheckCommand->execute();

			$neighborhood = $this->determineNeighborhood($location);
			$neighborhood?->addObject($object);
		}
	}

	public function updateObjectPosition(IGameObject $object, Vector $newLocation): void
	{
		$oldLocation = $object->getProperty('location');
		$oldNeighborhood = $this->determineNeighborhood($oldLocation);
		$newNeighborhood = $this->determineNeighborhood($newLocation);

		if ($newNeighborhood === null)
		{
			return;
		}

		$collisionCheckCommand = new CollisionCheckCommand($object, $this, $newLocation);
		$collisionCheckCommand->execute();

		if ($oldNeighborhood !== null && $oldNeighborhood !== $newNeighborhood)
		{
			$oldNeighborhood->removeObject($object);
			$newNeighborhood->addObject($object);
		}
	}

	public function determineNeighborhood(Vector $location): ?Neighborhood
	{
		$row = floor($location->getY() / $this->neighborhoodSize);
		$col = floor($location->getX() / $this->neighborhoodSize);

		return $this->neighborhoods[$row][$col] ?? null;
	}

	public function getObjectNeighborhood(IGameObject $object): ?Neighborhood
	{
		$location = $object->getProperty('location');

		return $this->determineNeighborhood($location);
	}

	private function initializeNeighborhoods(int $width, int $height): void
	{
		$rows = ceil($height / $this->neighborhoodSize);
		$cols = ceil($width / $this->neighborhoodSize);

		for ($i = 0; $i < $rows; $i++)
		{
			for ($j = 0; $j < $cols; $j++)
			{
				$this->neighborhoods[$i][$j] = new Neighborhood();
			}
		}
	}
}
