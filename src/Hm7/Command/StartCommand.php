<?php

use App\Hm7\Server;

class StartCommand
{
	private $server;

	public function __construct(Server $server)
	{
		$this->server = $server;
	}

	public function execute()
	{
		$this->server->start();
	}
}
