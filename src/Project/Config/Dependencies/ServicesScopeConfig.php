<?php

namespace App\Project\Config\Dependencies;

use App\Project\Services\RoutingService;

class ServicesScopeConfig implements IScopeConfig
{
	public function getDependencies(): array
	{
		return [
			'RoutingService' => function () {
				return new RoutingService();
			},
		];
	}
}
