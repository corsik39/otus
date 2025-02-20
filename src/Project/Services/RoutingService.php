<?php

namespace App\Project\Services;

use App\Project\Services\Promotions\PromotionCommand;

class RoutingService
{
	private array $promotions = [];

	public function addPromotion(PromotionCommand $promotion): void
	{
		$this->promotions[] = $promotion;
	}

	public function processUrl(string $originalUrl, array $data): string
	{
		$redirectParams = [];

		foreach ($this->promotions as $promotion)
		{
			$param = $promotion->execute($data);
			if ($param !== null)
			{
				$redirectParams[] = $param;
			}
		}

		$finalRedirectParams = implode('&', $redirectParams);

		return $this->buildRedirectUrl($originalUrl, $finalRedirectParams ?: '');
	}

	private function buildRedirectUrl(string $originalUrl, string $redirectParams): string
	{
		$parsedUrl = parse_url($originalUrl);
		$scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
		$host = $parsedUrl['host'] ?? '';
		$path = $parsedUrl['path'] ?? '';

		return $scheme . $host . $path . ($redirectParams ? '?' . $redirectParams : '');
	}
}
