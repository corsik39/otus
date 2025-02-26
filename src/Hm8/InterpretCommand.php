<?php

namespace App\Hm8;

use App\Hm2\Command\ICommand;
use App\Hm2\GameObject;
use App\Hm2\IocBattle;
use App\Hm3\IocException;
use App\Hm5\Ioc;

class InterpretCommand
{
	private IncomingMessageDto $incomingMessage;
	private GameObject $gameObject;

	public function __construct(IncomingMessageDto $incomingMessage)
	{
		$this->registerScope($incomingMessage);

		IocBattle::registry();
		IocException::registry();

		$this->gameObject = new GameObject();
		$this->incomingMessage = $incomingMessage;
	}

	public function execute(): void
	{
		$command = $this->createCommand();

		if ($command)
		{
			Ioc::resolve('CommandQueue.add', $command);
		}
	}

	private function registerScope($incomingMessage): void
	{
		$userId = $incomingMessage->userId;
		if ($userId)
		{
			Ioc::resolve('Scopes.New', $incomingMessage->userId)->execute();
		}
	}

	private function createCommand(): ?ICommand
	{
		$operationId = $this->incomingMessage->operationId;
		$commandName = ucfirst($operationId);

		if (!Ioc::hasDependency($commandName))
		{
			return null;
		}

		$this->setGameObjectProperty();

		return Ioc::resolve($commandName, $this->gameObject);

	}

	private function setGameObjectProperty(): void
	{
		foreach ($this->incomingMessage->parameters as $parameter)
		{
			$IocName = ucfirst($parameter['action']);
			if (Ioc::hasDependency($IocName))
			{
				$properties = $parameter['properties'];
				['x' => $x, 'y' => $y] = $properties['data'];
				$this->gameObject->setProperty($properties['name'], Ioc::resolve($IocName, $x, $y));
			}
		}
	}
}
