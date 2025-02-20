<?php

namespace App\Tests\Project\Utils;

use App\Project\Utils\ResponseFormatter;
use PHPUnit\Framework\TestCase;

class ResponseFormatterTest extends TestCase
{
	public function testFormatError(): void
	{
		$response = ResponseFormatter::formatError('Invalid request');
		$this->assertEquals('error', $response['status']);
		$this->assertEquals(404, $response['code']);
		$this->assertEquals('Invalid request', $response['message']);
	}

	public function testFormatSuccess(): void
	{
		$response = ResponseFormatter::formatSuccess('https://example.com/success');
		$this->assertEquals('success', $response['status']);
		$this->assertEquals(200, $response['code']);
		$this->assertEquals('https://example.com/success', $response['data']['redirect_url']);
	}
}
