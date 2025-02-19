<?php

namespace App\Project\Services;

use App\Project\Services\Parsers\HandlerInterface;

class RequestParser
{
	private $handlers;

	public function addHandler(HandlerInterface $handler): void
	{
		if ($this->handlers)
		{
			$this->handlers->setNext($handler);
		}
		else
		{
			$this->handlers = $handler;
		}
	}

	public function parse(): array
	{
		return $this->handlers ? $this->handlers->handle() : [];
	}
}
