<?php

namespace App\Project\Services\Routing;

interface RuleInterface
{
	public function matches(array $data): bool;
}
