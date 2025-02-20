<?php

namespace App\Project\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler
{
	private static string $secretKey = 'your_secret_key';

	public static function generateToken(array $payload): string
	{
		return JWT::encode($payload, self::$secretKey, 'HS256');
	}

	public static function validateToken(string $token): bool
	{
		try
		{
			JWT::decode($token, new Key(self::$secretKey, 'HS256'));

			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public static function getPayload(string $token): ?array
	{
		try
		{
			$decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));

			return (array)$decoded;
		}
		catch (\Exception $e)
		{
			return null;
		}
	}
}
