<?php

namespace App\Hm2\Command;

use App\Hm2\Interface\IMovingObject;
use App\Hm2\Vector;

class MoveCommand implements ICommand
{
	private ?IMovingObject $moving = null;

	public function __construct(IMovingObject $moving)
	{
		$this->moving = $moving;
	}

	public function execute(): void
	{
		$this->moving->setLocation(
			Vector::plus($this->moving->getLocation(), $this->moving->getVelocity())
		);
	}
}
