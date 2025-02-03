<?php

namespace App\Tests\Hm11;

use App\Hm11\Command\MoveToCommand;
use App\Hm11\Command\RunCommand;
use App\Hm11\State\MoveToState;
use App\Hm11\State\NormalState;
use App\Hm11\StatefulCommandQueue;
use App\Hm7\StopHardCommand;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
	public function testNormalStateWithStopHardCommand(): void
	{
		$queue = new StatefulCommandQueue();
		$queue->enqueue(new StopHardCommand($queue));

		$queue->start();

		$this->assertFalse($queue->isRunning(), "Queue should stop after StopHardCommand.");
	}

	public function testNormalStateWithMoveToCommand(): void
	{
		$queue = new StatefulCommandQueue();
		$targetQueue = new StatefulCommandQueue();
		$queue->enqueue(new MoveToCommand($targetQueue));

		$queue->start();

		$this->assertInstanceOf(MoveToState::class, $queue->getCurrentState(), "Queue should be in MoveToState after MoveToCommand.");
	}

	public function testMoveToStateWithRunCommand(): void
	{
		$queue = new StatefulCommandQueue();
		$targetQueue = new StatefulCommandQueue();
		$queue->enqueue(new MoveToCommand($targetQueue));
		$queue->enqueue(new RunCommand());

		$queue->start();

		$this->assertInstanceOf(NormalState::class, $queue->getCurrentState(), "Queue should return to NormalState after RunCommand.");
	}
}
