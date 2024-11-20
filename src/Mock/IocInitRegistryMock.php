<?php

namespace App\Mock;

use App\Hm5\Scopes\CommandRegistry;
use App\Hm5\Scopes\RegisterDependencyCommand;
use App\Hm5\Scopes\SetCurrentScopeCommand;

class IocInitRegistryMock
{
	public static function init(): void
	{
		CommandRegistry::register('IoC.Register', static function ($dependency, $resolver) {
			return new RegisterDependencyCommand($dependency, $resolver);
		});
		CommandRegistry::register('Scopes.New', static function ($scopeId) {
			return new SetCurrentScopeCommand($scopeId, true);
		});
		CommandRegistry::register('Scopes.Current', static function ($scopeId) {
			return new SetCurrentScopeCommand($scopeId, false);
		});
	}
}
