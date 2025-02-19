<?php

namespace App\Tests\Project\Utils;

use App\Project\Utils\City\Cities;
use PHPUnit\Framework\TestCase;

class CitiesTest extends TestCase
{
	public function testSetCsvFilePathAndGetCities(): void
	{
		$cities = new Cities();
		$cityList = $cities->getCities();

		$this->assertCount(1117, $cityList);
		$this->assertEquals('Адыгейск', $cityList[0]['name']);
		$this->assertEquals('adygeysk', $cityList[0]['translit']);
		$this->assertEquals('ADY', $cityList[0]['code']);

		$this->assertEquals('Знаменск', $cityList[40]['name']);
		$this->assertEquals('znamensk', $cityList[40]['translit']);
		$this->assertEquals('ZNA', $cityList[40]['code']);
	}
}
