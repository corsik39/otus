<?php

namespace App\Hm11\State;

use App\Hm11\Command\MoveToCommand;
use App\Hm11\StatefulCommandQueue;
use App\Hm2\Command\ICommand;
use App\Hm7\StopHardCommand;

class NormalState implements StateInterface
{
	public function handle(StatefulCommandQueue $queue, ICommand $command): ?StateInterface
	{
		if ($command instanceof StopHardCommand)
		{
			$command->execute();

			return null;
		}

		if ($command instanceof MoveToCommand)
		{
			return new MoveToState($command->getTargetQueue()); // Переход в состояние MoveTo
		}

		// Выполнение команды
		try
		{
			$command->execute();
		}
		catch (\Exception $exception)
		{
			// Обработка исключений
		}

		return $this; // Остаемся в текущем состоянии
	}
}
