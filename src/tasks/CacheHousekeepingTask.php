<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\tasks;

use jasonw4331\LuckPerms\LuckPerms;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;

class CacheHousekeepingTask extends Task{
	private LuckPerms $plugin;

	public function __construct(LuckPerms $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * Actions to execute when run
	 *
	 * @throws CancelTaskException
	 */
	public function onRun() : void{
		foreach($this->plugin->getUserManager()->getAll() as $user){
			$user->getCachedData()->performCacheCleanup();
		}
		foreach($this->plugin->getGroupManager()->getAll() as $group){
			$group->getCachedData()->performCacheCleanup();
		}
	}
}
