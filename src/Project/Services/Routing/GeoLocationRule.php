<?php

namespace App\Project\Services\Routing;

class GeoLocationRule implements RuleInterface
{
	private string $region;

	public function __construct(string $region)
	{
		$this->region = $region;
	}

	public function matches(array $data): bool
	{
		return isset($data['region']) && $data['region'] === $this->region;
	}
}
