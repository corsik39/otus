<?php

namespace App\Project\Config;

use App\Project\Config\Dependencies\ServicesScopeConfig;

class ServicesScopeRegistrar implements IRegistrar
{
	public static function registerScope(string $name = 'services'): void
	{
		$scopes = [
			ServicesScopeConfig::class,
		];

		foreach ($scopes as $scope)
		{
			ScopeLoader::registry($name, new $scope());
		}
	}
}
