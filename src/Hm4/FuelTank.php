<?php

namespace App\Hm4;

use App\Hm5\Ioc;

class FuelTank
{
	private int $fuel;

	private BurnStrategy $burnStrategy;

	public function __construct(int $fuel, BurnStrategy $burnStrategy)
	{
		$this->fuel = $fuel;
		$this->burnStrategy = $burnStrategy;
	}

	public function getFuelLevel(): int
	{
		return $this->fuel;
	}

	public function burn(): void
	{
		if ($this->isEmpty())
		{
			Ioc::resolve('ExceptionCommand', "There is not enough fuel.")->execute();
		}

		$this->burnStrategy->burn($this->fuel);
	}

	public function isEmpty(): bool
	{
		return $this->fuel <= 0;
	}
}
