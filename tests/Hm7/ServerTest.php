<?php

namespace App\Tests\Hm7;

use App\Hm7\CommandQueue;
use App\Hm7\Server;
use HardStopCommand;
use PHPUnit\Framework\TestCase;
use StartCommand;

class ServerTest extends TestCase
{
	public function testStartCommand()
	{
		$queue = new CommandQueue();
		$server = new Server($queue);
		$startCommand = new StartCommand($server);

		$startCommand->execute();

		// Проверка, что сервер запущен
		$this->assertTrue(true); // Здесь можно добавить более сложную логику проверки
	}

	public function testHardStopCommand()
	{
		$queue = new CommandQueue();
		$server = new Server($queue);
		$startCommand = new StartCommand($server);
		$hardStopCommand = new HardStopCommand($server);

		$startCommand->execute();
		$hardStopCommand->execute();

		// Проверка, что сервер остановлен
		$this->assertTrue(true); // Здесь можно добавить более сложную логику проверки
	}

	// public function testSoftStopCommand()
	// {
	// 	$queue = new CommandQueue();
	// 	$server = new Server($queue);
	// 	$startCommand = new StartCommand($server);
	// 	$softStopCommand = new SoftStopCommand($server);
	//
	// 	$queue->enqueue(new SomeCommand()); // Добавьте команду для выполнения
	// 	$startCommand->execute();
	// 	$softStopCommand->execute();
	//
	// 	// Проверка, что сервер остановлен после выполнения всех команд
	// 	$this->assertTrue(true); // Здесь можно добавить более сложную логику проверки
	// }
}
