<?php

namespace App\Project\Services\Promotions;

use App\Project\Services\Routing\RuleInterface;

class RegionPromotionCommand implements PromotionCommand
{
	private RuleInterface $rule;
	private string $parameter;

	public function __construct(RuleInterface $rule, string $parameter)
	{
		$this->rule = $rule;
		$this->parameter = $parameter;
	}

	public function execute(array $data): ?string
	{
		if ($this->rule->matches($data))
		{
			return 'region=' . $this->parameter;
		}

		return null;
	}
}
