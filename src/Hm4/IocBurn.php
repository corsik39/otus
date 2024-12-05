<?php

namespace App\Hm4;

use App\Hm4\Command\BurnFuelCommand;
use App\Hm4\Command\CheckFuelCommand;
use App\Hm4\Command\ExceptionCommand;
use App\Hm5\interface\IIocRegistry;
use App\Hm5\Ioc;

class IocBurn implements IIocRegistry
{
	public static function registry(): void
	{
		Ioc::resolve('IoC.Register', 'BurnFuelCommand', static function (...$args) {
			return new BurnFuelCommand(...$args);
		})->execute();

		Ioc::resolve('IoC.Register', 'CheckFuelCommand', static function (...$args) {
			return new CheckFuelCommand(...$args);
		})->execute();

		Ioc::resolve('IoC.Register', 'ExceptionCommand', static function (...$args) {
			return new ExceptionCommand(...$args);
		})->execute();
	}
}
