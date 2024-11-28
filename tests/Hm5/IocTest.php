<?php

namespace App\Tests\Hm5;

use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\Command\MoveCommand;
use App\Hm2\GameObject;
use App\Hm2\Vector;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use PHPUnit\Framework\TestCase;

class IoCTest extends TestCase
{
	public function testContainer()
	{
		// Создаем и переключаемся на новый скоуп
		Ioc::resolve('Scopes.New', 'scope1')->execute();

		// Регистрируем зависимость в текущем скоупе
		Ioc::resolve('IoC.Register', 'TestMove', static function () {
			$gameObject = new GameObject();
			$gameObject->setProperty('location', new Vector(12, 5));
			$gameObject->setProperty('velocity', new Vector(-7, 3));

			$adapter = new MovingObjectAdapter($gameObject);
			$moveCommand = new MoveCommand($adapter);
			$moveCommand->execute();

			return $adapter->getLocation();
		})->execute();

		// Разрешаем зависимость
		$newLocation = Ioc::resolve('TestMove', []);
		$this->assertEquals([5, 8], [$newLocation->getX(), $newLocation->getY()]);

	}

	public function testScope2()
	{
		// Создаем и переключаемся на новый скоуп
		Ioc::resolve('Scopes.New', 'scope2')->execute();

		// Проверяем, что в новом скоупе зависимости нет
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage("Dependency MoveCommand not found.");
		Ioc::resolve('MoveCommand', []);
	}

	public function testCurrentScopes()
	{
		//Проверяем что в scope1 MoveCommand существует
		Ioc::resolve('Scopes.Current', 'scope1')->execute();
		$hasDependency = Ioc::hasDependency('TestMove');

		$this->assertTrue($hasDependency);
	}

	protected function setUp(): void
	{
		IocInit::registry();
	}
}
