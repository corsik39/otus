<?php

namespace App\Tests\Project\Config;

use App\Hm5\Ioc;
use App\Project\Config\Dependencies\DeviceScopeConfig;
use App\Project\Config\ScopeLoader;
use PHPUnit\Framework\TestCase;

class ScopeLoaderTest extends TestCase
{
	public function testRegistry(): void
	{
		$scopeConfig = new DeviceScopeConfig();
		ScopeLoader::registry('promo', $scopeConfig);

		$this->assertTrue(Ioc::isScopeRegistered('promo'));
		$this->assertTrue(Ioc::hasDependency('DeviceTypeRule'));
		$this->assertTrue(Ioc::hasDependency('DevicePromotionCommand'));
	}

	public function testRegistryWithInvalidResolver(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage("Dependency resolver for InvalidDependency is not callable.");

		$scopeConfig = $this->createMock(DeviceScopeConfig::class);
		$scopeConfig->method('getDependencies')->willReturn([
			'InvalidDependency' => 'not_callable',
		]);

		ScopeLoader::registry('promo', $scopeConfig);
	}

	protected function setUp(): void
	{
		Ioc::reset();
	}
}
