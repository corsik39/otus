<?php

namespace App\Hm11\State;

use App\Hm11\StatefulCommandQueue;
use App\Hm2\Command\ICommand;

interface StateInterface
{
	public function handle(StatefulCommandQueue $queue, ICommand $command): ?StateInterface;
}
