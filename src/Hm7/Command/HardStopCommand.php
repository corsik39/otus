<?php

use App\Hm7\Server;

class HardStopCommand
{
	private $server;

	public function __construct(Server $server)
	{
		$this->server = $server;
	}

	public function execute()
	{
		$this->server->hardStop();
	}
}
