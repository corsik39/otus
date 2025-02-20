<?php

namespace App\Project\Middleware;

interface IMiddleware
{
	public function handle(array $request, callable $next);
}
