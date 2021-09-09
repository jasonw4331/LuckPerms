<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\tasks;

use jasonwynn10\LuckPerms\LuckPerms;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;

class CacheHousekeepingTask extends Task {
	private LuckPerms $plugin;

	/**
	 * @param LuckPerms $plugin
	 */
	public function __construct(LuckPerms $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * Actions to execute when run
	 *
	 * @throws CancelTaskException
	 */
	public function onRun() : void{
		foreach($this->plugin->getUserManager()->getAll() as $user) {
			$user->getCachedData()->performCacheCleanup();
		}
		foreach($this->plugin->getGroupManager()->getAll() as $group) {
			$group->getCachedData()->performCacheCleanup();
		}
	}
}