<?php

namespace App\Mock;

use App\Hm2\interface\IGameObject;

class GameObjectMock implements IGameObject
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
