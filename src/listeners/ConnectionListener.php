<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\listeners;

use jasonw4331\LuckPerms\LuckPerms;
use jasonw4331\LuckPerms\util\AbstractConnectionListener;
use pocketmine\event\Listener;

class ConnectionListener extends AbstractConnectionListener implements Listener{

	public function __construct(LuckPerms $param){ }
}
