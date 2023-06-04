<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\http;

use pocketmine\utils\InternetException;

class BytesocksClient extends AbstractHttpClient{

	private string $httpUrl;
	private string $wsUrl;

	public function __construct(string $host, private string $userAgent){
		$this->httpUrl = 'https://'.$host.'/';
		$this->wsUrl = 'wss://'.$host.'/';
	}

	public function createSocket(\Closure $webSocketListener) : Socket {
		$sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if($sock === false){
			throw new InternetException("Failed to get internal IP: " . trim(socket_strerror(socket_last_error())));
		}
		// open web socket using wss protocol

	}

}
