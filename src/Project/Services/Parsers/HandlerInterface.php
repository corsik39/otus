<?php

namespace App\Project\Services\Parsers;

interface HandlerInterface
{
	public function setNext(HandlerInterface $handler): HandlerInterface;

	public function handle(): array;
}
