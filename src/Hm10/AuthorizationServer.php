<?php

namespace App\Hm10;

use Firebase\JWT\JWT;

class AuthorizationServer
{
	private string $secretKey = 'your_secret_key';
	private array $battles = [];

	public function createBattle(array $participants): string
	{
		$gameId = uniqid('battle_', true);
		$this->battles[$gameId] = $participants;

		return $gameId;
	}

	public function issueToken(string $userId, string $gameId): ?string
	{
		if (isset($this->battles[$gameId]) && in_array($userId, $this->battles[$gameId], true))
		{
			$payload = [
				'iss' => 'auth_server',
				'sub' => $userId,
				'gameId' => $gameId,
				'iat' => time(),
				'exp' => time() + 3600
			];

			return JWT::encode($payload, $this->secretKey, 'HS256');
		}

		return null;
	}
}
