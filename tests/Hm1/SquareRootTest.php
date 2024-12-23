<?php

namespace App\Tests\Hm1;

use App\Hm1\SquareRoot;
use PHPUnit\Framework\TestCase;

class SquareRootTest extends TestCase
{
	private SquareRoot $quadratic;

	public function testNoRoots(): void
	{
		$result = $this->quadratic->calculate(1, 0, 1);
		$this->assertEmpty($result, "Expected no roots for equation x^2 + 1 = 0");
	}

	public function testOneRoot(): void
	{
		$result = $this->quadratic->calculate(1, 2, 1);
		$this->assertCount(1, $result, "Expected one root for equation x^2 + 2x + 1 = 0");
		$this->assertEquals(-1, $result[0], "Expected root to be -1");
	}

	public function testTwoRoots(): void
	{
		$result = $this->quadratic->calculate(1, -3, 2);
		$this->assertCount(2, $result, "Expected two roots for equation x^2 - 3x + 2 = 0");
		$this->assertContains(1.0, $result, "Expected root to be 1");
		$this->assertContains(2.0, $result, "Expected root to be 2");
	}

	public function testCoefficientANotZero(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->quadratic->calculate(0, 2, 1);
	}

	public function testDiscriminantNaN(): void
	{
		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(NAN, 2, 1);
	}

	public function testDiscriminantInfinite(): void
	{
		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(INF, 2, 1);
	}

	public function testSpecialValues(): void
	{
		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(NAN, 2, 1);

		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(INF, 2, 1);

		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(1, NAN, 1);

		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(1, INF, 1);

		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(1, 2, NAN);

		$this->expectException(\RuntimeException::class);
		$this->quadratic->calculate(1, 2, INF);
	}

	protected function setUp(): void
	{
		$this->quadratic = new SquareRoot();
	}
}
