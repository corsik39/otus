<?php

namespace App\Hm2\Interface;

interface IGameObject
{
	public function getProperty(string $string);

	public function setProperty(string $string, $value);
}
