<?php

namespace App\Hm10;

use App\Hm5\Ioc;
use App\Hm8\GameController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class GameServer extends GameController
{
	private string $secretKey = 'your_secret_key';
	private AuthorizationServer $authServer;

	public function verifyToken(string $jwt): ?array
	{
		try
		{
			$decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));

			return (array)$decoded;
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	public function setAuthServer(AuthorizationServer $authServer): void
	{
		$this->authServer = $authServer;
	}

	public function processMessage(string $jsonData, string $jwt): array
	{
		$decodedToken = $this->verifyToken($jwt);

		if (!$decodedToken)
		{
			return ['status' => 'error', 'message' => 'Invalid token'];
		}

		$message = IoC::resolve('IncomingMessage', $jsonData);
		if (!$message || !isset($message->gameId))
		{
			return ['status' => 'error', 'message' => 'Invalid message format'];
		}

		if ($decodedToken['gameId'] !== $message->gameId)
		{
			return ['status' => 'error', 'message' => 'Unauthorized operation for this game'];
		}

		return $this->receiveMessage($jsonData);
	}
}
