<?php

namespace App\Project\Config\Dependencies;

use App\Project\Services\Promotions\TimePromotionCommand;
use App\Project\Services\Routing\TimeBasedRule;

class TimeScopeConfig implements IScopeConfig
{
	public function getDependencies(): array
	{
		return [
			'TimeBasedRule' => function ($startTime, $endTime) {
				return new TimeBasedRule($startTime, $endTime);
			},
			'TimePromotionCommand' => function ($rule, $parameter) {
				return new TimePromotionCommand($rule, $parameter);
			},
		];
	}
}
