<?php

namespace App\Hm8;

use App\Hm2\IocBattle;
use App\Hm3\IocException;
use App\Hm5\IoC;

class GameController
{
	public function __construct()
	{
		IocBattle::registry();
		IocException::registry();
	}

	/**
	 * Пример JSON-формата сообщения:
	 * {
	 *     "header": {
	 *         "version": "1.0",
	 *         "timestamp": "2023-10-05T12:34:56Z",
	 *         "messageType": "command"
	 *     },
	 *     "body": {
	 *         "gameId": "123",
	 *         "objectId": "456",
	 *         "operation": {
	 *             "id": "move",
	 *             "parameters": {}
	 *         }
	 *     }
	 * }
	 *
	 * @param string $jsonData
	 * @return array|string[]
	 */
	public function receiveMessage(string $jsonData): array
	{
		try
		{
			IoC::resolve(
				'InterpretCommand',
				IoC::resolve('IncomingMessage', $jsonData)
			)->execute();

			return ['status' => 'success'];
		}
		catch (\InvalidArgumentException $e)
		{
			return ['status' => 'error', 'message' => $e->getMessage()];
		}
	}
}
