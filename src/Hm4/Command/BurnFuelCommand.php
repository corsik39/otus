<?php

namespace App\Hm4\Command;

use App\Hm2\Command\ICommand;
use App\Hm4\FuelTank;

class BurnFuelCommand implements ICommand
{
	private FuelTank $fuelTank;

	public function __construct(FuelTank $fuelTank)
	{
		$this->fuelTank = $fuelTank;
	}

	public function execute(): void
	{
		$this->fuelTank->burn();
	}
}
