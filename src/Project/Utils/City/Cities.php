<?php

namespace App\Project\Utils\City;

namespace App\Project\Utils\City;

class Cities
{
	private string $csvFilePath = __DIR__ . '/city.csv';

	private static function transliterate(string $text): string
	{
		$cyrillic = [
			'а',
			'б',
			'в',
			'г',
			'д',
			'е',
			'ё',
			'ж',
			'з',
			'и',
			'й',
			'к',
			'л',
			'м',
			'н',
			'о',
			'п',
			'р',
			'с',
			'т',
			'у',
			'ф',
			'х',
			'ц',
			'ч',
			'ш',
			'щ',
			'ъ',
			'ы',
			'ь',
			'э',
			'ю',
			'я',
			'А',
			'Б',
			'В',
			'Г',
			'Д',
			'Е',
			'Ё',
			'Ж',
			'З',
			'И',
			'Й',
			'К',
			'Л',
			'М',
			'Н',
			'О',
			'П',
			'Р',
			'С',
			'Т',
			'У',
			'Ф',
			'Х',
			'Ц',
			'Ч',
			'Ш',
			'Щ',
			'Ъ',
			'Ы',
			'Ь',
			'Э',
			'Ю',
			'Я'
		];

		$latin = [
			'a',
			'b',
			'v',
			'g',
			'd',
			'e',
			'yo',
			'zh',
			'z',
			'i',
			'y',
			'k',
			'l',
			'm',
			'n',
			'o',
			'p',
			'r',
			's',
			't',
			'u',
			'f',
			'h',
			'ts',
			'ch',
			'sh',
			'sch',
			'',
			'y',
			'',
			'e',
			'yu',
			'ya',
			'A',
			'B',
			'V',
			'G',
			'D',
			'E',
			'Yo',
			'Zh',
			'Z',
			'I',
			'Y',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'R',
			'S',
			'T',
			'U',
			'F',
			'H',
			'Ts',
			'Ch',
			'Sh',
			'Sch',
			'',
			'Y',
			'',
			'E',
			'Yu',
			'Ya'
		];

		return str_replace($cyrillic, $latin, $text);
	}

	private static function generateCityCode(string $cityName): string
	{
		return strtoupper(substr($cityName, 0, 3));
	}

	public function getCities(): array
	{
		$cities = [];
		if (empty($this->csvFilePath) || !file_exists($this->csvFilePath))
		{
			return $cities;
		}

		if (($handle = fopen($this->csvFilePath, 'rb')) !== false)
		{
			$headers = fgetcsv($handle);
			while (($data = fgetcsv($handle)) !== false)
			{
				$row = array_combine($headers, $data);

				if (isset($row['city']))
				{
					$translitCity = self::transliterate($row['city']);
					$cities[] = [
						'name' => $row['city'],
						'translit' => strtolower($translitCity),
						'code' => self::generateCityCode($translitCity),
					];
				}
			}
			fclose($handle);
		}

		return $cities;
	}
}
