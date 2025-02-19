<?php

namespace App\Project\Services\Routing;

class DeviceTypeRule implements RuleInterface
{
	private string $deviceType;

	public function __construct(string $deviceType)
	{
		$this->deviceType = $deviceType;
	}

	public function matches(array $data): bool
	{
		return isset($data['device_type']) && $data['device_type'] === $this->deviceType;
	}
}
