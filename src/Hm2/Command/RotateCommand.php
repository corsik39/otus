<?php

namespace App\Hm2\Command;

use App\Hm2\Angle;
use App\Hm2\interface\IRotatingObject;

class RotateCommand implements ICommand
{
	private ?IRotatingObject $rotating = null;

	public function __construct(IRotatingObject $rotating)
	{
		$this->rotating = $rotating;
	}

	public function execute(): void
	{
		$currentAngle = $this->rotating->getAngle();
		$angularVelocity = $this->rotating->getAngularVelocity();

		$newAngleValue = ($currentAngle->getAngle() + $angularVelocity->getAngle()) % $currentAngle->getDirectionsNumber();

		$newAngle = new Angle($newAngleValue, $currentAngle->getDirectionsNumber());
		$this->rotating->setAngle($newAngle);
	}
}
