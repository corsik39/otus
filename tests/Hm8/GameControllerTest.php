<?php

namespace App\Tests\Hm8;

use App\Hm2\Command\MoveCommand;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use App\Hm8\IocGameController;
use PHPUnit\Framework\TestCase;

class GameControllerTest extends TestCase
{
	public function testInterpretCommand()
	{
		Ioc::resolve(
			'InterpretCommand',
			Ioc::resolve('IncomingMessage', $this->mockJson)
		)->execute();
		$queue = Ioc::resolve('CommandQueue');
		$this->assertInstanceOf(MoveCommand::class, $queue->dequeue());
	}

	public function testGameController(): void
	{
		$response = Ioc::resolve('ReceiveMessage', $this->mockJson);
		$this->assertEquals(['status' => 'success'], $response);
		$queue = Ioc::resolve('CommandQueue');
		$this->assertInstanceOf(MoveCommand::class, $queue->dequeue());
	}

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
				'operation' => [
					'id' => 'move',
					'parameters' => [
						[
							'action' => 'Vector',
							'properties' => [
								'name' => 'location',
								'data' => [
									'x' => 12,
									'y' => 5
								]
							]
						],
						[
							'action' => 'Vector',
							'properties' => [
								'name' => 'velocity',
								'data' => [
									'x' => -7,
									'y' => 3
								]
							]
						]
					]
				]
			]
		], JSON_THROW_ON_ERROR);
	}
}
