<?php

namespace App\Project\Services\Parsers;

use App\Project\Utils\Device\Devices;

class UserAgentHandler extends AbstractHandler
{
	protected function canHandle(): bool
	{
		return $this->serverDataExtractor->getUserAgent() !== null;
	}

	protected function extractData(): array
	{
		$userAgent = $this->serverDataExtractor->getUserAgent();
		$devices = Devices::getDevices();

		foreach ($devices as $deviceType => $models)
		{
			foreach ($models as $model => $pattern)
			{
				if (preg_match("/$pattern/i", $userAgent))
				{
					return [
						'device_type' => $deviceType,
						'device_model' => $model,
					];
				}
			}
		}

		return ['device_type' => 'unknown', 'device_model' => 'unknown'];
	}
}
