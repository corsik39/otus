<?php

namespace App\Tests\Hm13;

use App\Hm13\ShootCommand;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use App\Hm8\IocGameController;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase
{
	private string $mockJson;

	public function setUp(): void
	{
		IocInit::registry();
		IocGameController::registry();

		$this->mockJson = json_encode([
			'header' => [
				'version' => '1.0',
				'timestamp' => '2023-10-05T12:34:56Z',
				'messageType' => 'command'
			],
			'body' => [
				'gameId' => '123',
				'objectId' => '456',
				'userId' => 'user789',
				'operation' => [
					'id' => 'shoot',
					'parameters' => []
				]
			]
		], JSON_THROW_ON_ERROR);
	}

	public function testGameController(): void
	{
		$response = Ioc::resolve('ReceiveMessage', $this->mockJson);
		// Проверяем что сообщение валидное
		$this->assertEquals(['status' => 'success'], $response);
		// Проверяем что мы в нужной области видимости для определённого пользователя
		$this->assertEquals('user789', Ioc::getCurrentScopeId());
		$queue = Ioc::resolve('CommandQueue');

		//Проверяем что команда выстрела стоит в очереди
		$this->assertInstanceOf(ShootCommand::class, $queue->dequeue());
	}
}
