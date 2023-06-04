<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms;

use CortexPE\Commando\BaseCommand;
use jasonwynn10\LuckPerms\commands\group\GroupParentCommand;
use jasonwynn10\LuckPerms\commands\track\TrackParentCommand;
use jasonwynn10\LuckPerms\commands\user\UserParentCommand;
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
