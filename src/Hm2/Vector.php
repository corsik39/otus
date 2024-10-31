<?php

namespace App\Hm2;

class Vector
{
	private float $x;
	private float $y;

	public function __construct(float $x, float $y)
	{
		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * @throws \Exception
	 */
	public static function plus(Vector $v1, Vector $v2): Vector
	{
		return new Vector($v1->getX() + $v2->getX(), $v1->getY() + $v2->getY());
	}

	public function getX(): float
	{
		return $this->x;
	}

	public function getY(): float
	{
		return $this->y;
	}
}
