<?php

namespace App\Project\Controllers;

use App\Project\Middleware\AuthMiddleware;
use App\Project\Middleware\MiddlewarePipeline;
use App\Project\Services\RoutingService;

class GatewayController
{
	private RoutingService $routingService;
	private MiddlewarePipeline $middlewarePipeline;

	public function __construct(RoutingService $routingService, MiddlewarePipeline $middlewarePipeline)
	{
		$this->routingService = $routingService;
		$this->middlewarePipeline = $middlewarePipeline;
	}

	public function handleRequest(): void
	{
		$this->middlewarePipeline->addMiddleware(new AuthMiddleware());

		$requestData = $_SERVER;

		$this->middlewarePipeline->handle($requestData);

		$redirectUrl = $this->routingService->processUrl('http://example.com', $requestData);

		header("Location: " . $redirectUrl);

		if (php_sapi_name() !== 'cli')
		{
			exit();
		}
	}
}
