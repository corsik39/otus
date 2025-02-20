<?php

namespace App\Project\Config\Dependencies;

use App\Project\Services\Parsers\ClientIpHandler;
use App\Project\Services\Parsers\GeoIpHandler;
use App\Project\Services\Parsers\UserAgentHandler;

class ParseHandleScopeConfig implements IScopeConfig
{
	public function getDependencies(): array
	{
		return [
			'GeoIpHandler' => function ($serverData) {
				return new GeoIpHandler($serverData);
			},
			'UserAgentHandler' => function ($serverData) {
				return new UserAgentHandler($serverData);
			},
			'ClientIpHandler' => function ($serverData) {
				return new ClientIpHandler($serverData);
			},
		];
	}
}
