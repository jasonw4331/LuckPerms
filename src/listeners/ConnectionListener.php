<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\listeners;

use jasonwynn10\LuckPerms\LuckPerms;
use jasonwynn10\LuckPerms\util\AbstractConnectionListener;
use pocketmine\event\Listener;

class ConnectionListener extends AbstractConnectionListener implements Listener{

	public function __construct(LuckPerms $param){ }
}
