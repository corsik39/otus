<?php

namespace App\Tests\Project\Middleware;

use App\Project\Middleware\AuthMiddleware;
use App\Project\Utils\JwtHandler;
use PHPUnit\Framework\TestCase;

class AuthMiddlewareTest extends TestCase
{
	public function testHandleWithValidToken(): void
	{
		$middleware = new AuthMiddleware();
		$request = [];
		$next = function ($request) {
			return $request;
		};

		$token = JwtHandler::generateToken(['user_id' => 1]);
		$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;

		$result = $middleware->handle($request, $next);

		$this->assertEquals($request, $result);
	}

	public function testHandleWithInvalidToken(): void
	{
		$this->expectException(\Exception::class);

		$middleware = new AuthMiddleware();
		$request = [];
		$next = function ($request) {
			return $request;
		};

		$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer invalid_token';

		$middleware->handle($request, $next);
	}

	public function testHandleWithoutToken(): void
	{
		$this->expectException(\Exception::class);

		$middleware = new AuthMiddleware();
		$request = [];
		$next = function ($request) {
			return $request;
		};

		unset($_SERVER['HTTP_AUTHORIZATION']);

		$middleware->handle($request, $next);
	}
}
