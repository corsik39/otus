<?php

namespace App\Hm5;

use App\Hm5\Scopes\CommandRegistry;

class Ioc
{
	public static array $scopes = [];
	public static ?string $currentScope = null;

	public static function resolve(string $key, ...$args): mixed
	{
		if (CommandRegistry::has($key))
		{
			return CommandRegistry::get($key, ...$args);
		}

		return self::resolveDependency($key, ...$args);
	}

	public static function registerScope(string $scopeId): void
	{
		if (!isset(self::$scopes[$scopeId]))
		{
			self::$scopes[$scopeId] = [];
		}

		self::$currentScope = $scopeId;
	}

	public static function setCurrentScope(string $scopeId): void
	{
		if (!isset(self::$scopes[$scopeId]))
		{
			throw new \RuntimeException("Scope {$scopeId} does not exist.");
		}
		self::$currentScope = $scopeId;
	}

	public static function addDependency(string $scopeId, string $dependency, callable $resolver): void
	{
		if (!isset(self::$scopes[$scopeId]))
		{
			throw new \RuntimeException("Scope {$scopeId} does not exist.");
		}
		self::$scopes[$scopeId][$dependency] = $resolver;
	}

	public static function hasDependency(string $dependencyKey): bool
	{
		return isset(self::$scopes[self::$currentScope][$dependencyKey]);
	}

	public static function reset(): void
	{
		self::$scopes = [];
		self::$currentScope = null;
	}

	public static function isScopeRegistered(string $scopeId): bool
	{
		return isset(self::$scopes[$scopeId]);
	}

	private static function resolveDependency(string $dependencyKey, ...$args): mixed
	{
		if (self::hasDependency($dependencyKey))
		{
			return call_user_func(self::$scopes[self::$currentScope][$dependencyKey], ...$args);
		}

		throw new \RuntimeException("Dependency {$dependencyKey} not found.");
	}
}
