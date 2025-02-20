<?php

namespace App\Project\Config;

use App\Project\Config\Dependencies\DeviceScopeConfig;
use App\Project\Config\Dependencies\GeoLocationScopeConfig;
use App\Project\Config\Dependencies\TimeScopeConfig;

class PromoRuleRegistrar implements IRegistrar
{
	public static function registerScope(string $name = 'promo'): void
	{
		$scopes = [
			DeviceScopeConfig::class,
			GeoLocationScopeConfig::class,
			TimeScopeConfig::class,
		];

		foreach ($scopes as $scope)
		{
			ScopeLoader::registry($name, new $scope());
		}
	}
}
