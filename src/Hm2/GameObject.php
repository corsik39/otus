<?php

namespace App\Hm2;

use App\Hm2\interface\IGameObject;

class GameObject implements IGameObject
{
	private array $properties = [];

	public function getProperty(string $string)
	{
		return $this->properties[$string] ?? null;
	}

	public function setProperty(string $string, $value): void
	{
		$this->properties[$string] = $value;
	}
}
