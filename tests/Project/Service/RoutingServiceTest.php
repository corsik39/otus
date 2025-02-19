<?php

namespace App\Tests\Project\Service;

use App\Hm5\Ioc;
use App\Project\Config\PromoRuleRegistrar;
use App\Project\Config\ServicesScopeRegistrar;
use PHPUnit\Framework\TestCase;

class RoutingServiceTest extends TestCase
{
	public function testProcessUrlWithAllRulesAndPromotions(): void
	{
		Ioc::setCurrentScope('services');
		$routingService = Ioc::resolve('RoutingService');
		Ioc::setCurrentScope('promo');

		$deviceTypeRule = Ioc::resolve('DeviceTypeRule', 'mobile');
		$devicePromotion = Ioc::resolve('DevicePromotionCommand', $deviceTypeRule, 'mobile');
		$routingService->addPromotion($devicePromotion);

		$geoLocationRule = Ioc::resolve('GeoLocationRule', 'Chelyabinsk');
		$geoLocationPromotion = Ioc::resolve('RegionPromotionCommand', $geoLocationRule, 'chelyabinsk');
		$routingService->addPromotion($geoLocationPromotion);

		$timeBasedRule = Ioc::resolve('TimeBasedRule', '00:00', '23:59');
		$timePromotion = Ioc::resolve('TimePromotionCommand', $timeBasedRule, 'daytime');
		$routingService->addPromotion($timePromotion);

		$originalUrl = 'http://example.com';
		$data = [
			'device_type' => 'mobile',
			'region' => 'Chelyabinsk',
		];

		$processedUrl = $routingService->processUrl($originalUrl, $data);

		$this->assertEquals('http://example.com?device=mobile&region=chelyabinsk&time=daytime', $processedUrl);
	}

	public function testProcessUrlWithIncorrectDeviceType(): void
	{
		Ioc::setCurrentScope('services');
		$routingService = Ioc::resolve('RoutingService');
		Ioc::setCurrentScope('promo');

		$deviceTypeRule = Ioc::resolve('DeviceTypeRule', 'mobile');
		$devicePromotion = Ioc::resolve('DevicePromotionCommand', $deviceTypeRule, 'mobile');
		$routingService->addPromotion($devicePromotion);

		$originalUrl = 'http://example.com';
		$data = [
			'device_type' => 'desktop',
		];

		$processedUrl = $routingService->processUrl($originalUrl, $data);

		$this->assertEquals('http://example.com', $processedUrl);
	}

	public function testProcessUrlWithIncorrectRegion(): void
	{
		Ioc::setCurrentScope('services');
		$routingService = Ioc::resolve('RoutingService');
		Ioc::setCurrentScope('promo');

		$geoLocationRule = Ioc::resolve('GeoLocationRule', 'Chelyabinsk');
		$geoLocationPromotion = Ioc::resolve('RegionPromotionCommand', $geoLocationRule, 'chelyabinsk');
		$routingService->addPromotion($geoLocationPromotion);

		$originalUrl = 'http://example.com';
		$data = [
			'region' => 'Moscow',
		];

		$processedUrl = $routingService->processUrl($originalUrl, $data);

		$this->assertEquals('http://example.com', $processedUrl);
	}

	public function testProcessUrlWithIncorrectTime(): void
	{
		Ioc::setCurrentScope('services');
		$routingService = Ioc::resolve('RoutingService');
		Ioc::setCurrentScope('promo');

		$timeBasedRule = Ioc::resolve('TimeBasedRule', '00:00', '01:00');
		$timePromotion = Ioc::resolve('TimePromotionCommand', $timeBasedRule, 'night');
		$routingService->addPromotion($timePromotion);

		$originalUrl = 'http://example.com';
		$data = [];

		$processedUrl = $routingService->processUrl($originalUrl, $data);

		$this->assertEquals('http://example.com', $processedUrl);
	}

	protected function setUp(): void
	{
		parent::setUp();
		PromoRuleRegistrar::registerScope();
		ServicesScopeRegistrar::registerScope();
	}
}
