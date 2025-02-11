<?php

namespace App\Hm2;

use App\Hm12\GameFieldManager;
use App\Hm2\Interface\IGameObject;

class GameObject implements IGameObject
{
	private ?GameFieldManager $gameFieldManager = null;
	private array $properties = [];

	public function getProperty(string $string)
	{
		return $this->properties[$string] ?? null;
	}

	public function setProperty(string $string, $value): void
	{
		$this->properties[$string] = $value;
	}

	public function getGameFieldManager(): ?GameFieldManager
	{
		return $this->gameFieldManager;
	}

	public function setGameFieldManager(GameFieldManager $gameFieldManager): GameFieldManager
	{
		return $this->gameFieldManager = $gameFieldManager;
	}
}
