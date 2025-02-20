<?php

namespace App\Tests\Project\Config;

use App\Hm5\Ioc;
use App\Project\Config\PromoRuleRegistrar;
use PHPUnit\Framework\TestCase;

class ScopeRegistrarTest extends TestCase
{
	public function testRegisterAllScopes(): void
	{
		PromoRuleRegistrar::registerScope('promo');

		$this->assertTrue(Ioc::isScopeRegistered('promo'));
		$this->assertTrue(Ioc::hasDependency('DeviceTypeRule'));
		$this->assertTrue(Ioc::hasDependency('DevicePromotionCommand'));
		$this->assertTrue(Ioc::hasDependency('GeoLocationRule'));
		$this->assertTrue(Ioc::hasDependency('RegionPromotionCommand'));
		$this->assertTrue(Ioc::hasDependency('TimeBasedRule'));
		$this->assertTrue(Ioc::hasDependency('TimePromotionCommand'));
	}

	protected function setUp(): void
	{
		Ioc::reset();
	}
}
