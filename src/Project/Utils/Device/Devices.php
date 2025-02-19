<?php

namespace App\Project\Utils\Device;

class Devices
{
	public static function getDevices(): array
	{
		return [
			'mobile' => [
				'iPhone' => 'iPhone',
				'Android' => 'Android',
			],
			'desktop' => [
				'Mac' => 'Macintosh',
				'Windows' => 'Windows NT',
			],
			'tablet' => [
				'iPad' => 'iPad',
			],
		];;
	}
}
