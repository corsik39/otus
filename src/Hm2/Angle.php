<?php

namespace App\Hm2;

class Angle
{
	private int $angle;
	private int $directionsNumber;

	public function __construct(int $angle, int $directionsNumber)
	{
		$this->angle = $angle;
		$this->directionsNumber = $directionsNumber;
	}

	public function getAngle(): int
	{
		return $this->angle;
	}

	public function getDirectionsNumber(): int
	{
		return $this->directionsNumber;
	}

	public function setAngle(int $angle): void
	{
		$this->angle = $angle;
	}
}
