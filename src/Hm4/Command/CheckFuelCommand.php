<?php

namespace App\Hm4\Command;

use App\Hm2\Command\ICommand;
use App\Hm4\FuelTank;
use App\Hm5\Ioc;

class CheckFuelCommand implements ICommand
{
	private FuelTank $fuelTank;
	private int $checkFuelAmount;

	public function __construct(FuelTank $fuelTank, ?int $checkFuelAmount = 0)
	{
		$this->fuelTank = $fuelTank;
		$this->checkFuelAmount = $checkFuelAmount;
	}

	public function execute(): void
	{
		if ($this->fuelTank->isEmpty())
		{
			Ioc::resolve('ExceptionCommand', "Fuel is expended or tank is broken.")->execute();
		}

		if ($this->fuelTank->getFuelLevel() < $this->checkFuelAmount)
		{
			Ioc::resolve('ExceptionCommand', "There is not enough fuel.")->execute();
		}
	}
}
