<?php

namespace App\Hm2\Adapter;

use App\Hm2\Angle;
use App\Hm2\interface\IGameObject;
use App\Hm2\interface\IRotatingObject;
use Exception;

class RotatingObjectAdapter implements IRotatingObject
{
	private ?IGameObject $gameObject = null;

	public function __construct(IGameObject $rotating)
	{
		$this->gameObject = $rotating;
	}

	public function getAngle(): Angle
	{
		$angle = $this->gameObject->getProperty('angle');
		if (!$angle instanceof Angle)
		{
			throw new \RuntimeException("Unable to read angle.");
		}

		return $angle;
	}

	public function getAngularVelocity(): Angle
	{
		$angularVelocity = $this->gameObject->getProperty('angularVelocity');
		if (!$angularVelocity instanceof Angle)
		{
			throw new \RuntimeException("Unable to read angular velocity.");
		}

		return $angularVelocity;
	}

	public function setAngle(Angle $newAngle): void
	{
		try
		{
			$this->gameObject->setProperty('angle', $newAngle);
		}
		catch (Exception $e)
		{
			throw new \RuntimeException($e);
		}
	}
}
