<?php

namespace App\Hm2;

use App\Hm2\Command\MoveCommand;
use App\Hm5\interface\IIocRegistry;
use App\Hm5\Ioc;

class IocBattle implements IIocRegistry
{
	public static function registry(): void
	{
		Ioc::resolve('IoC.Register', 'MoveCommand', static function ($adapter) {
			return new MoveCommand($adapter);
		})->execute();
	}
}
