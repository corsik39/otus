<?php

namespace App\Project\Services;

class ServerDataExtractor
{
	private array $serverData;

	public function __construct(array $serverData = [])
	{
		$this->serverData = $serverData ?: $_SERVER;
	}

	public function getGeoIpRegion(): ?string
	{
		return $this->getServerValue('HTTP_X_GEOIP_REGION');
	}

	public function getUserAgent(): ?string
	{
		return $this->getServerValue('HTTP_USER_AGENT');
	}

	public function getClientIp(): ?string
	{
		if ($this->hasServerValue('HTTP_CLIENT_IP'))
		{
			return $this->getServerValue('HTTP_CLIENT_IP');
		}
		if ($this->hasServerValue('HTTP_X_FORWARDED_FOR'))
		{
			return trim(explode(',', $this->getServerValue('HTTP_X_FORWARDED_FOR'))[0]);
		}

		return $this->getServerValue('REMOTE_ADDR');
	}

	public function setServerData(array $data): void
	{
		$this->serverData = $data;
	}

	private function getServerValue(string $key): ?string
	{
		return $this->serverData[$key] ?? null;
	}

	private function hasServerValue(string $key): bool
	{
		return isset($this->serverData[$key]);
	}
}
