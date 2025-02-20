<?php

namespace App\Project\Services\Promotions;

interface PromotionCommand
{
	public function execute(array $data): ?string;
}
