<?php

namespace App\Project\Config;

use App\Hm5\Ioc;
use App\Project\Config\Dependencies\IScopeConfig;

class ScopeLoader
{
	public static function registry(string $scopeName, IScopeConfig $scopeConfig): void
	{
		Ioc::registerScope($scopeName);

		$dependencies = $scopeConfig->getDependencies();
		foreach ($dependencies as $key => $resolver)
		{
			if (is_callable($resolver))
			{
				Ioc::addDependency($scopeName, $key, $resolver);
			}
			else
			{
				throw new \InvalidArgumentException("Dependency resolver for {$key} is not callable.");
			}
		}
	}
}
