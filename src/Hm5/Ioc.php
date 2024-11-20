<?php

namespace App\Hm5;

use App\Hm5\Scopes\CommandRegistry;

class Ioc
{
	public static array $scopes = [];
	public static string $currentScope;

	public static function resolve($key, ...$args)
	{
		if (CommandRegistry::has($key))
		{
			return CommandRegistry::get($key, ...$args);
		}

		return self::resolveDependency($key, $args);
	}

	public static function registerScope($scopeId): void
	{
		self::$scopes[$scopeId] = [];
		self::$currentScope = $scopeId;
	}

	public static function setCurrentScope($scopeId): void
	{
		if (!isset(self::$scopes[$scopeId]))
		{
			throw new \RuntimeException("Scope {$scopeId} does not exist.");
		}
		self::$currentScope = $scopeId;
	}

	public static function addDependency($scopeId, $dependency, callable $resolver): void
	{
		if (!isset(self::$scopes[$scopeId]))
		{
			throw new \RuntimeException("Scope {$scopeId} does not exist.");
		}
		self::$scopes[$scopeId][$dependency] = $resolver;
	}

	public static function hasDependency($dependencyKey): bool
	{
		return self::$currentScope && isset(self::$scopes[self::$currentScope][$dependencyKey]);
	}

	private static function resolveDependency($dependencyKey, $args)
	{
		if (self::hasDependency($dependencyKey))
		{
			return call_user_func(self::$scopes[self::$currentScope][$dependencyKey], $args);
		}

		throw new \RuntimeException("Dependency {$dependencyKey} not found.");
	}
}
