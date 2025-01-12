<?php

namespace App\Hm8;

class IncomingMessageDto
{
	public string $version;
	public string $timestamp;
	public string $messageType;
	public string $gameId;
	public string $objectId;
	public string $operationId;
	public array $parameters;

	public function __construct(string $jsonData)
	{
		$data = json_decode($jsonData, true);

		if (json_last_error() !== JSON_ERROR_NONE)
		{
			throw new \InvalidArgumentException('Invalid JSON data: ' . json_last_error_msg());
		}

		$this->validate($data);

		$this->version = $data['header']['version'];
		$this->timestamp = $data['header']['timestamp'];
		$this->messageType = $data['header']['messageType'];
		$this->gameId = $data['body']['gameId'];
		$this->objectId = $data['body']['objectId'];
		$this->operationId = $data['body']['operation']['id'];
		$this->parameters = $data['body']['operation']['parameters'];
	}

	private function validate(array $data): void
	{
		$errors = [];

		if (!isset($data['header']['version']) || !is_string($data['header']['version']))
		{
			$errors[] = 'The version field is required and must be a string.';
		}

		if (!isset($data['header']['timestamp']) || !is_string($data['header']['timestamp']))
		{
			$errors[] = 'The timestamp field is required and must be a string.';
		}

		if (!isset($data['header']['messageType']) || !is_string($data['header']['messageType']))
		{
			$errors[] = 'The messageType field is required and must be a string.';
		}

		if (!isset($data['body']['gameId']) || !is_string($data['body']['gameId']))
		{
			$errors[] = 'The gameId field is required and must be a string.';
		}

		if (!isset($data['body']['objectId']) || !is_string($data['body']['objectId']))
		{
			$errors[] = 'The objectId field is required and must be a string.';
		}

		if (!isset($data['body']['operation']['id']) || !is_string($data['body']['operation']['id']))
		{
			$errors[] = 'The operation id field is required and must be a string.';
		}

		if (!isset($data['body']['operation']['parameters']) || !is_array($data['body']['operation']['parameters']))
		{
			$errors[] = 'The parameters field is required and must be an array.';
		}

		if (!empty($errors))
		{
			throw new \InvalidArgumentException(implode(' ', $errors));
		}
	}
}
