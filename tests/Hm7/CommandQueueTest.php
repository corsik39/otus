<?php

namespace App\Tests\Hm7;

use App\Hm2\Adapter\MovingObjectAdapter;
use App\Hm2\GameObject;
use App\Hm2\Vector;
use App\Hm3\Command\LogCommand;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use App\Hm8\IocGameController;
use PHPUnit\Framework\TestCase;

class CommandQueueTest extends TestCase
{
	public function testStartCommand(): void
	{
		$this->addCommands();
		$queue = Ioc::resolve('CommandQueue');
		Ioc::resolve('StartCommand', $queue);
		$queueException = Ioc::resolve('CommandQueueException');

		$this->assertTrue($queue->isEmpty());
		$this->assertInstanceOf(LogCommand::class, $queueException->dequeue());
		$this->assertTrue($queue->isRunning());
	}

	public function testStopHardCommand(): void
	{
		$this->addCommands();
		$queue = Ioc::resolve('CommandQueue');

		Ioc::resolve('ReceiveMessage', $this->mockMoveJson);
		Ioc::resolve('CommandQueue.add', Ioc::resolve('MoveCommand', $this->movingAdapter)); //exception to log
		Ioc::resolve('ReceiveMessage', $this->mockRotateJson);

		Ioc::resolve('StartCommand', $queue);
		Ioc::resolve('StopHardCommand', $queue);

		Ioc::resolve('ReceiveMessage', $this->mockMoveJson);
		Ioc::resolve('ReceiveMessage', $this->mockRotateJson);

		$this->assertFalse($queue->isRunning());
		$this->assertFalse($queue->isEmpty());
	}

	public function testStopSoftCommand(): void
	{
		$this->addCommands();
		$queue = Ioc::resolve('CommandQueue');

		Ioc::resolve('ReceiveMessage', $this->mockMoveJson);
		Ioc::resolve('CommandQueue.add', Ioc::resolve('MoveCommand', $this->movingAdapter)); //exception to log
		Ioc::resolve('ReceiveMessage', $this->mockRotateJson);
		Ioc::resolve('StartCommand', $queue);
		Ioc::resolve('StopSoftCommand', $queue);
		Ioc::resolve('ReceiveMessage', $this->mockMoveJson);
		Ioc::resolve('ReceiveMessage', $this->mockRotateJson);

		$this->assertFalse($queue->isRunning());
		$this->assertFalse($queue->isEmpty());
	}

	public function addCommands(): void
	{
		Ioc::resolve('ReceiveMessage', $this->mockMoveJson);
		Ioc::resolve('CommandQueue.add', Ioc::resolve('MoveCommand', $this->movingAdapter)); //exception to log
		Ioc::resolve('ReceiveMessage', $this->mockRotateJson);
		Ioc::resolve('ReceiveMessage', $this->mockMoveJson);
		Ioc::resolve('ReceiveMessage', $this->mockRotateJson);
	}

	public function setUp(): void
	{
		IocInit::registry();
		IocGameController::registry();

		$gameObject = new GameObject();
		$gameObject->setProperty('velocity', new Vector(-7, 3));

		$this->movingAdapter = new MovingObjectAdapter($gameObject);

		$this->mockMoveJson = json_encode([
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

		$this->mockRotateJson = json_encode([
			'header' => [
				'version' => '1.0',
				'timestamp' => '2023-10-05T12:34:56Z',
				'messageType' => 'command'
			],
			'body' => [
				'gameId' => '123',
				'objectId' => '456',
				'operation' => [
					'id' => 'rotate',
					'parameters' => [
						[
							'action' => 'Angle',
							'properties' => [
								'name' => 'angle',
								'data' => [
									'x' => 0,
									'y' => 360
								]
							]
						],
						[
							'action' => 'Angle',
							'properties' => [
								'name' => 'angularVelocity',
								'data' => [
									'x' => 90,
									'y' => 360
								]
							]
						]
					]
				]
			]
		], JSON_THROW_ON_ERROR);
	}
}
