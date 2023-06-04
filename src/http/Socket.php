<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\http;

final class Socket{

	public function __construct(private string $channelId, private \Socket $socket) {}

	/**
	 * @return string
	 */
	public function getChannelId() : string{
		return $this->channelId;
	}

	/**
	 * @return \Socket
	 */
	public function getSocket() : \Socket{
		return $this->socket;
	}

}