<?php

namespace App\Tests\Hm10;

use App\Hm10\AuthorizationServer;
use App\Hm10\GameServer;
use App\Hm5\Ioc;
use App\Hm5\IocInit;
use App\Hm8\IocGameController;
use PHPUnit\Framework\TestCase;

class AuthorizationServerTest extends TestCase
{
	public function testIssueToken(): void
	{
		$authServer = new AuthorizationServer();
		$battleId = $authServer->createBattle(['user1', 'user2']);
		$token = $authServer->issueToken('user1', $battleId);

		$this->assertNotNull($token);
	}

	public function testIssueTokenInvalidUser(): void
	{
		$authServer = new AuthorizationServer();
		$battleId = $authServer->createBattle(['user1', 'user2']);
		$token = $authServer->issueToken('user3', $battleId);

		$this->assertNull($token);
	}

	public function testCreateBattle(): void
	{
		$authServer = new AuthorizationServer();
		$participants = ['user1', 'user2', 'user3'];
		$battleId = $authServer->createBattle($participants);

		$this->assertNotEmpty($battleId);
	}

	public function testVerifyToken(): void
	{
		$authServer = new AuthorizationServer();
		$gameController = new GameServer();

		$gameId = $authServer->createBattle(['user1', 'user2']);
		$token = $authServer->issueToken('user1', $gameId);

		$decodedToken = $gameController->verifyToken($token);

		$this->assertNotNull($decodedToken);
		$this->assertEquals('user1', $decodedToken['sub']);
		$this->assertEquals($gameId, $decodedToken['gameId']);
	}

	public function testVerifyTokenInvalid(): void
	{
		$gameController = new GameServer();
		$invalidToken = 'invalid.jwt.token';

		$decodedToken = $gameController->verifyToken($invalidToken);

		$this->assertNull($decodedToken);
	}

	public function testReceiveMessageWithValidToken(): void
	{
		$authServer = new AuthorizationServer();
		$gameController = new GameServer();
		$gameController->setAuthServer($authServer);

		$battleId = $authServer->createBattle(['user1', 'user2']);
		$token = $authServer->issueToken('user1', $battleId);

		$jsonMessage = $this->createPayload($battleId);
		$response = $gameController->processMessage($jsonMessage, $token);

		$this->assertEquals(['status' => 'success'], $response);
	}

	public function testReceiveMessageWithInvalidToken(): void
	{
		$gameController = new GameServer(new AuthorizationServer());
		$invalidToken = 'invalid.jwt.token';

		$jsonMessage = $this->createPayload('invalid_gameId');
		$response = $gameController->processMessage($jsonMessage, $invalidToken);

		$this->assertEquals([
			'status' => 'error',
			'message' => 'Invalid token'
		], $response);
	}

	public function createPayload($gameId): string
	{
		return json_encode([
			'header' => [
				'version' => '1.0',
				'timestamp' => '2023-10-05T12:34:56Z',
				'messageType' => 'command'
			],
			'body' => [
				'gameId' => $gameId,
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

	public function setUp(): void
	{
		IocInit::registry();
		IocGameController::registry();
	}
}
