<?php

namespace App\Hm2\Interface;

use App\Hm2\Vector;

interface IMovingObject
{
	public function getLocation(): Vector;

	public function getVelocity(): Vector;

	public function setLocation(Vector $newLocation): void;
}
