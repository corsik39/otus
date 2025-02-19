<?php

namespace App\Project\Services\Parsers;

use App\Project\Services\ServerDataExtractor;

abstract class AbstractHandler implements HandlerInterface
{
	protected $nextHandler;
	protected ServerDataExtractor $serverDataExtractor;

	public function __construct($serverData)
	{
		$this->serverDataExtractor = new ServerDataExtractor($serverData);
	}

	public function setNext(HandlerInterface $handler): HandlerInterface
	{
		$this->nextHandler = $handler;

		return $handler;
	}

	public function handle(): array
	{
		$result = [];

		if ($this->canHandle())
		{
			$result = $this->extractData();
		}

		if ($this->nextHandler)
		{
			$nextResult = $this->nextHandler->handle();
			$result = array_merge($result, $nextResult);
		}

		return $result;
	}

	abstract protected function canHandle(): bool;

	abstract protected function extractData(): array;
}
