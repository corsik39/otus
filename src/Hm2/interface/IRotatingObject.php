<?php

namespace App\Hm2\interface;

use App\Hm2\Angle;

interface IRotatingObject
{
	public function getAngle(): Angle;

	public function getAngularVelocity(): Angle;

	public function setAngle(Angle $newAngle): void;
}
