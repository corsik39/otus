<?php

namespace App\Hm12;

use App\Hm2\Interface\IGameObject;

class Neighborhood
{
	private array $objects = [];

	public function addObject(IGameObject $object): void
	{
		$this->objects[spl_object_hash($object)] = $object;
	}

	public function removeObject(IGameObject $object): void
	{
		unset($this->objects[spl_object_hash($object)]);
	}

	public function getObjects(): array
	{
		return $this->objects;
	}
}
