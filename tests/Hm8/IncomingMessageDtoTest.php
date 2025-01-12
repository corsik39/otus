<?php

use App\Hm8\IncomingMessageDto;
use PHPUnit\Framework\TestCase;

class IncomingMessageDtoTest extends TestCase
{
	public function testValidJsonData()
	{
		$jsonData = json_encode([
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
							'action' => 'location',
							'properties' => [
								'x' => 12,
								'y' => 5
							]
						],
						[
							'action' => 'vector',
							'property' => [
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

		$dto = new IncomingMessageDto($jsonData);

		$this->assertEquals('1.0', $dto->version);
		$this->assertEquals('2023-10-05T12:34:56Z', $dto->timestamp);
		$this->assertEquals('command', $dto->messageType);
		$this->assertEquals('123', $dto->gameId);
		$this->assertEquals('456', $dto->objectId);
		$this->assertEquals('move', $dto->operationId);
		$this->assertNotEquals([
				[
					'action' => 'vector',
					'properties' => [
						'x' => 12,
						'y' => 5
					]
				],
				[
					'action' => 'vector',
					'property' => [
						'name' => 'Vector',
						'data' => [
							'x' => -7,
							'y' => 3
						]
					]
				]
			]
			, $dto->parameters);
	}

	public function testInvalidJsonData()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid JSON data');

		$invalidJsonData = '{"header": "invalid json}';
		new IncomingMessageDto($invalidJsonData);
	}

	public function testMissingFields()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The gameId field is required and must be a string.');

		$jsonData = json_encode([
			'header' => [
				'version' => '1.0',
				'timestamp' => '2023-10-05T12:34:56Z',
				'messageType' => 'command'
			],
			'body' => [
				'objectId' => '456',
				'operation' => [
					'id' => 'move',
					'parameters' => [
						'direction' => 'north',
						'speed' => 2
					]
				]
			]
		], JSON_THROW_ON_ERROR);

		new IncomingMessageDto($jsonData);
	}
}
