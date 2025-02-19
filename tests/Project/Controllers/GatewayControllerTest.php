<?php

namespace App\Tests\Project\Controllers;

use App\Project\Controllers\GatewayController;
use App\Project\Middleware\MiddlewarePipeline;
use App\Project\Services\RoutingService;
use PHPUnit\Framework\TestCase;

class GatewayControllerTest extends TestCase
{
	public function testHandleRequest(): void
	{
		$routingService = $this->createMock(RoutingService::class);
		$middlewarePipeline = $this->createMock(MiddlewarePipeline::class);

		$routingService->method('processUrl')->willReturn('http://example.com/redirected');
		$middlewarePipeline->method('handle')->willReturn([]);

		$controller = new GatewayController($routingService, $middlewarePipeline);

		ob_start();
		$controller->handleRequest();
		ob_end_clean();

		$headers = xdebug_get_headers();
		$this->assertContains('Location: http://example.com/redirected', $headers);
	}
}
