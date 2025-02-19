<?php

namespace App\Tests\Project\Service;

use App\Hm5\Ioc;
use App\Project\Config\HandlerRegistrar;
use App\Project\Services\RequestParser;
use PHPUnit\Framework\TestCase;

class RequestParserTest extends TestCase
{
	public function testParseWithCompleteChain(): void
	{
		$serverData = [
			'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X)',
			'HTTP_X_GEOIP_REGION' => 'Chelyabinsk',
			'REMOTE_ADDR' => '192.168.1.1',
		];

		Ioc::setCurrentScope('parse');
		$userAgentHandler = Ioc::resolve('UserAgentHandler', $serverData);
		$geoIpHandler = Ioc::resolve('GeoIpHandler', $serverData);
		$clientIpHandler = Ioc::resolve('ClientIpHandler', $serverData);

		$userAgentHandler
			->setNext($geoIpHandler)
			->setNext($clientIpHandler)
		;

		$requestParser = new RequestParser();
		$requestParser->addHandler($userAgentHandler);

		$parsedData = $requestParser->parse();

		$this->assertEquals('mobile', $parsedData['device_type']);
		$this->assertEquals('iPhone', $parsedData['device_model']);
		$this->assertEquals('chelyabinsk', $parsedData['region']);
		$this->assertEquals('192.168.1.1', $parsedData['client_ip']);
	}

	public function testParseUnknownDevice(): void
	{
		$serverData = [
			'HTTP_USER_AGENT' => 'Some Unknown Device',
			'HTTP_X_GEOIP_REGION' => 'Moscow',
			'REMOTE_ADDR' => '127.0.0.1',
		];

		Ioc::setCurrentScope('parse');
		$userAgentHandler = Ioc::resolve('UserAgentHandler', $serverData);
		$geoIpHandler = Ioc::resolve('GeoIpHandler', $serverData);
		$clientIpHandler = Ioc::resolve('ClientIpHandler', $serverData);

		$userAgentHandler
			->setNext($geoIpHandler)
			->setNext($clientIpHandler)
		;

		$requestParser = new RequestParser();
		$requestParser->addHandler($userAgentHandler);

		$parsedData = $requestParser->parse();

		$this->assertEquals('unknown', $parsedData['device_type']);
		$this->assertEquals('unknown', $parsedData['device_model']);
		$this->assertEquals('moscow', $parsedData['region']);
		$this->assertEquals('127.0.0.1', $parsedData['client_ip']);
	}

	protected function setUp(): void
	{
		parent::setUp();
		HandlerRegistrar::registerScope();
	}
}
