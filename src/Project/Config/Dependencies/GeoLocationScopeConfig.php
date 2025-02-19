<?php

namespace App\Project\Config\Dependencies;

use App\Project\Services\Promotions\RegionPromotionCommand;
use App\Project\Services\Routing\GeoLocationRule;

class GeoLocationScopeConfig implements IScopeConfig
{
	public function getDependencies(): array
	{
		return [
			'GeoLocationRule' => function ($region) {
				return new GeoLocationRule($region);
			},
			'RegionPromotionCommand' => function ($rule, $parameter) {
				return new RegionPromotionCommand($rule, $parameter);
			},
		];
	}
}
