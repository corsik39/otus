<?php

namespace App\Project\Middleware;

use App\Project\Utils\JwtHandler;

class AuthMiddleware implements IMiddleware
{
	public function handle(array $request, callable $next)
	{
		$headers = getallheaders();
		if (!isset($headers['Authorization']))
		{
			throw new \RuntimeException('Unauthorized', 401);
		}

		$token = explode(" ", $headers['Authorization'])[1];
		if (!JwtHandler::validateToken($token))
		{
			throw new \RuntimeException('Unauthorized', 401);
		}

		return $next($request);
	}
}

if (!function_exists('getallheaders'))
{
	function getallheaders(): array
	{
		$headers = [];
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}

		return $headers;
	}
}
