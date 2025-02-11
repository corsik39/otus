<?php

namespace App\Hm2\Interface;

use App\Hm12\GameFieldManager;

interface IGameObject
{
	public function getProperty(string $string);

	public function setProperty(string $string, $value);

	public function getGameFieldManager(): ?GameFieldManager;
}
