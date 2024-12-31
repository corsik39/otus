<?php

namespace App\Hm7;

use parallel\Runtime;

class Server
{
	private $queue;
	private $runtimes = [];
	private $stop = false;

	public function __construct(CommandQueue $queue, int $threadCount = 4)
	{
		$this->queue = $queue;
		for ($i = 0; $i < $threadCount; $i++)
		{
			$this->runtimes[] = new Runtime();
		}
	}

	public function start()
	{
		foreach ($this->runtimes as $runtime)
		{
			$runtime->run(function ($queue) {
				while (true)
				{
					try
					{
						$command = $queue->dequeue();
						$command->execute();
					}
					catch (\Exception $e)
					{
						// Обработка исключения
					}
				}
			}, [$this->queue]);
		}
	}

	public function hardStop()
	{
		$this->stop = true;
		$this->queue->close();
	}

	public function softStop()
	{
		while ($this->queue->count() > 0)
		{
			// Ждем завершения всех команд
		}
		$this->hardStop();
	}
}
