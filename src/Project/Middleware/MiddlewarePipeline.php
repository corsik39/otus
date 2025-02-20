<?php

namespace App\Project\Middleware;

class MiddlewarePipeline
{
	private array $middleware = [];

	public function addMiddleware(IMiddleware $middleware): void
	{
		$this->middleware[] = $middleware;
	}

	public function handle(array $request)
	{
		$pipeline = array_reduce(
			array_reverse($this->middleware),
			static fn($next, $middleware) => static fn($request) => $middleware->handle($request, $next),
			static fn($request) => $request
		);

		return $pipeline($request);
	}
}
