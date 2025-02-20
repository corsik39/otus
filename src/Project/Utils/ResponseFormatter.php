<?php

namespace App\Project\Utils;

class ResponseFormatter
{
	public static function formatError(string $message, int $code = 404): array
	{
		return [
			'status' => 'error',
			'code' => $code,
			'message' => $message,
		];
	}

	public static function formatSuccess(string $url, int $code = 200): array
	{
		return [
			'status' => 'success',
			'code' => $code,
			'data' => [
				'redirect_url' => $url,
			],
		];
	}
}
