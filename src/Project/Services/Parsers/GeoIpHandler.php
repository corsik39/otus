<?php

namespace App\Project\Services\Parsers;

class GeoIpHandler extends AbstractHandler
{
	protected function canHandle(): bool
	{
		return $this->serverDataExtractor->getGeoIpRegion() !== null;
	}

	protected function extractData(): array
	{
		return ['region' => strtolower($this->serverDataExtractor->getGeoIpRegion() ?? '')];
	}
}
