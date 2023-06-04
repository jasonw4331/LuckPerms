<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\tasks;

use jasonw4331\LuckPerms\LuckPerms;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;

class ExpireTemporaryTask extends Task{
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
		$groupChanges = false;
		foreach($this->plugin->getGroupManager()->getAll() as $group){
			if($group->auditTemporaryNodes()){
				$this->plugin->getStorage()->saveGroup($group);
				$groupChanges = true;
			}
		}
		foreach($this->plugin->getUserManager()->getAll() as $user){
			if($user->auditTemporaryNodes()){
				$this->plugin->getStorage()->saveUser($user);
			}
		}

		if($groupChanges){
			$this->plugin->getGroupManager()->invalidateAllGroupCaches();
			$this->plugin->getUserManager()->invalidateAllUserCaches();
		}
	}
}
