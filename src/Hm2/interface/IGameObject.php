<?php

namespace App\Hm2\interface;

interface IGameObject
{
	public function getProperty(string $string);

	public function setProperty(string $string, $value);
}
