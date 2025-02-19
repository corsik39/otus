<?php

namespace App\Project\Config\Dependencies;

interface IScopeConfig
{
	public function getDependencies(): array;
}
