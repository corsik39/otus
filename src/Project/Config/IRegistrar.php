<?php

namespace App\Project\Config;

interface IRegistrar
{
	public static function registerScope(string $name = 'promo'): void;
}
