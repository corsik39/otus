<?php

namespace App\Hm1;

final class SquareRoot
{
	public function calculate(float $a, float $b, float $c, float $e = 1e-5): ?array
	{
		// Проверка, что коэффициент 'a' не равен нулю
		if (abs($a) <= $e)
		{
			throw new \InvalidArgumentException("Коэффициент 'a' не должен быть равен нулю.");
		}

		// Вычисляем дискриминант
		$discriminant = $b ** 2 - 4 * $a * $c;

		// Проверка на NaN
		if (is_nan($discriminant))
		{
			throw new \RuntimeException("Дискриминант является NaN, проверьте входные данные.");
		}

		// Проверка на бесконечность
		if (is_infinite($discriminant))
		{
			throw new \RuntimeException("Дискриминант является бесконечностью, проверьте входные данные.");
		}

		// Если дискриминант меньше нуля с учетом эпсилон, корней нет
		if ($discriminant < -$e)
		{
			return [];
		}

		// Если дискриминант близок к нулю с учетом эпсилон, один корень
		if (abs($discriminant) <= $e)
		{
			$x = -$b / (2 * $a);

			return [$x];
		}

		// Если дискриминант больше нуля, два корня
		$x1 = (-$b + sqrt($discriminant)) / (2 * $a);
		$x2 = (-$b - sqrt($discriminant)) / (2 * $a);

		return [$x1, $x2];
	}
}
