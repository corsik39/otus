<?php

namespace App\Hm4;

use App\Hm5\Ioc;

class BurnStrategy
{
	private int $amountFuelBurned;

	public function __construct(int $amountFuelBurned)
	{
		$this->amountFuelBurned = $amountFuelBurned;
	}

	public function burn(&$fuel): void
	{
		if ($this->amountFuelBurned > $fuel)
		{
			Ioc::resolve('ExceptionCommand', "There is not enough fuel.")->execute();
		}

		$fuel -= $this->amountFuelBurned;
	}
}
