<?php

namespace App\Hm5\Scopes;

use App\Hm2\Command\ICommand;
use App\Hm5\Ioc;

class SetCurrentScopeCommand implements ICommand
{
	private string $scopeId;
	private mixed $createNew;

	public function __construct($scopeId, $createNew = false)
	{
		$this->scopeId = $scopeId;
		$this->createNew = $createNew;
	}

	public function execute(): void
	{
		if ($this->createNew || !Ioc::hasScope($this->scopeId))
		{
			Ioc::registerScope($this->scopeId);
		}
		else
		{
			Ioc::setCurrentScope($this->scopeId);
		}
	}
}
