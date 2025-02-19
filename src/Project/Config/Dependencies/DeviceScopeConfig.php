<?php

namespace App\Project\Config\Dependencies;

use App\Project\Services\Promotions\DevicePromotionCommand;
use App\Project\Services\Routing\DeviceTypeRule;

class DeviceScopeConfig implements IScopeConfig
{
	public function getDependencies(): array
	{
		return [
			'DeviceTypeRule' => function ($deviceType) {
				return new DeviceTypeRule($deviceType);
			},
			'DevicePromotionCommand' => function ($rule, $parameter) {
				return new DevicePromotionCommand($rule, $parameter);
			},
		];
	}
}
