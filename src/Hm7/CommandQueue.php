<?php

namespace App\Hm7;

use Grpc\Channel;
use parallel\Channel;

class CommandQueue
{
	private $channel;

	public function __construct()
	{
		// $this->channel = Channel::make('commands', Channel::Infinite);
	}

	public function enqueue($command)
	{
		$this->channel->send($command);
	}

	public function dequeue()
	{
		return $this->channel->recv();
	}

	public function close()
	{
		$this->channel->close();
	}
}
