<?php

namespace App\Project\Services\Routing;

class TimeBasedRule implements RuleInterface
{
	private string $startTime;
	private string $endTime;

	public function __construct(string $startTime, string $endTime)
	{
		$this->startTime = $startTime;
		$this->endTime = $endTime;
	}

	public function matches(array $data): bool
	{
		$currentTime = new \DateTime();
		$startTime = new \DateTime($this->startTime);
		$endTime = new \DateTime($this->endTime);

		return $currentTime >= $startTime && $currentTime <= $endTime;
	}
}
