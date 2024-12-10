<?php

namespace App\Hm2\Adapter;

use App\Hm2\Interface\IGameObject;
use App\Hm2\Interface\IMovingObject;
use App\Hm2\Vector;
use Exception;

class MovingObjectAdapter implements IMovingObject
{
	private ?IGameObject $gameObject = null;

	public function __construct(IGameObject $moving)
	{
		$this->gameObject = $moving;
	}

	public function getLocation(): Vector
	{
		$location = $this->gameObject->getProperty('location');
		if (!$location instanceof Vector)
		{
			throw new \RuntimeException("Unable to read location.");
		}

		return $location;
	}

	public function getVelocity(): Vector
	{
		$velocity = $this->gameObject->getProperty('velocity');
		if (!$velocity instanceof Vector)
		{
			throw new \RuntimeException("Unable to read velocity.");
		}

		return $velocity;
	}

	public function setLocation(Vector $newLocation): void
	{
		try
		{
			$this->gameObject->setProperty('location', $newLocation);
		}
		catch (Exception $e)
		{
			throw new \RuntimeException($e);
		}
	}
}
