<?php

namespace App\Hm2;

use App\Hm13\ShootCommand;
use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\Adapter\RotatingObjectAdapter;
use App\Hm2\Command\MoveCommand;
use App\Hm2\Command\RotateCommand;
use App\Hm2\Interface\IGameObject;
use App\Hm2\Interface\IMovingObject;
use App\Hm2\Interface\IRotatingObject;
use App\Hm5\interface\IIocRegistry;
use App\Hm5\Ioc;

class IocBattle implements IIocRegistry
{
	public static function registry(): void
	{
		Ioc::resolve('IoC.Register', 'MoveCommand', static function (IMovingObject $adapter) {
			return new MoveCommand($adapter);
		})->execute();

		Ioc::resolve('IoC.Register', 'Move', static function (IGameObject $gameObject) {
			$adapter = new MovingObjectAdapter($gameObject);

			return new MoveCommand($adapter);
		})->execute();

		Ioc::resolve('IoC.Register', 'RotateCommand', static function (IRotatingObject $adapter) {
			return new RotateCommand($adapter);
		})->execute();

		Ioc::resolve('IoC.Register', 'Rotate', static function (IGameObject $gameObject) {
			$adapter = new RotatingObjectAdapter($gameObject);

			return new RotateCommand($adapter);
		})->execute();

		Ioc::resolve('IoC.Register', 'Angle', static function (float $x, float $y) {
			return new Angle($x, $y);
		})->execute();

		Ioc::resolve('IoC.Register', 'Vector', static function (float $x, float $y) {
			return new Vector($x, $y);
		})->execute();

		Ioc::resolve('IoC.Register', 'Shoot', static function () {
			return new ShootCommand();
		})->execute();
	}
}
