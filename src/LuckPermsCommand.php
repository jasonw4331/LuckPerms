<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms;

use CortexPE\Commando\BaseCommand;
use jasonw4331\LuckPerms\commands\group\GroupParentCommand;
use jasonw4331\LuckPerms\commands\track\TrackParentCommand;
use jasonw4331\LuckPerms\commands\user\UserParentCommand;
use pocketmine\command\CommandSender;

class LuckPermsCommand extends BaseCommand{

	protected function prepare() : void{
		$this->setPermission('luckperms.command');
		$this->registerSubCommand(new UserParentCommand('user', ''));
		$this->registerSubCommand(new GroupParentCommand('group', ''));
		$this->registerSubCommand(new TrackParentCommand('track', ''));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		// TODO: Implement onRun() method.
	}
}
