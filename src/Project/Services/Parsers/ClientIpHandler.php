<?php

namespace App\Project\Services\Parsers;

class ClientIpHandler extends AbstractHandler
{
	protected function canHandle(): bool
	{
		return $this->serverDataExtractor->getClientIp() !== null;
	}

	protected function extractData(): array
	{
		return ['client_ip' => $this->serverDataExtractor->getClientIp()];
	}
}
