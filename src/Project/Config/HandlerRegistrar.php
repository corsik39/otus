<?php

namespace App\Project\Config;

use App\Project\Config\Dependencies\ParseHandleScopeConfig;

class HandlerRegistrar implements IRegistrar
{
	public static function registerScope(string $name = 'parse'): void
	{
		$scopes = [
			ParseHandleScopeConfig::class,
		];

		foreach ($scopes as $scope)
		{
			ScopeLoader::registry($name, new $scope());
		}
	}
}
