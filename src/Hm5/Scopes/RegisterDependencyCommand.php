<?php

namespace App\Hm5\Scopes;

use App\Hm2\Command\ICommand;
use App\Hm5\Ioc;

class RegisterDependencyCommand implements ICommand
{
	private string $dependency;
	private $resolver;

	public function __construct($dependency, callable $resolver)
	{
		$this->dependency = $dependency;
		$this->resolver = $resolver;
	}

	public function execute(): void
	{
		if (Ioc::$currentScope === null)
		{
			throw new \Exception("No current scope set.");
		}

		Ioc::addDependency(Ioc::$currentScope, $this->dependency, $this->resolver);
	}
}
