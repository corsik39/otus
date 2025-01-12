<?php

namespace App\Hm8;

use App\Hm5\interface\IIocRegistry;
use App\Hm5\Ioc;

class IocGameController implements IIocRegistry
{
	public static function registry(): void
	{
		Ioc::resolve('IoC.Register', 'InterpretCommand', static function (IncomingMessageDto $incomingData) {
			return new InterpretCommand($incomingData);
		})->execute();

		Ioc::resolve('IoC.Register', 'IncomingMessage', static function (string $jsonData) {
			return new IncomingMessageDto($jsonData);
		})->execute();

		Ioc::resolve('IoC.Register', 'ReceiveMessage', static function ($jsonData) {
			return (new GameController())->receiveMessage($jsonData);
		})->execute();
	}
}
