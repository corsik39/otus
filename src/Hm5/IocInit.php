<?php

namespace App\Hm5;

use App\Hm3\IocException;
use App\Hm5\interface\IIocRegistry;
use App\Hm5\Scopes\CommandRegistry;
use App\Hm5\Scopes\RegisterDependencyCommand;
use App\Hm5\Scopes\SetCurrentScopeCommand;

class IocInit implements IIocRegistry
{
	public static function registry(): void
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

		Ioc::resolve('Scopes.New', 'main')->execute();

		IocException::registry();
	}
}
