<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms;

use jasonwynn10\LuckPerms\api\util\Tristate;
use jasonwynn10\LuckPerms\locale\TranslationManager;
use jasonwynn10\LuckPerms\sender\Sender;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwnedTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class SenderFactory{
	use PluginOwnedTrait;

	protected function getUniqueID(CommandSender $sender) : UuidInterface{
		if($sender instanceof Player){
			return $sender->getUniqueId();
		}
		return Uuid::fromString(Sender::CONSOLE_UUID);
	}

	protected function getName(CommandSender $sender) : string{
		if($sender instanceof Player){
			return $sender->getName();
		}
		return Sender::CONSOLE_NAME;
	}

	protected function sendMessage(CommandSender $sender, Translatable|string $message) : void{
		$sender->sendMessage(TranslationManager::render($message, $sender->getLanguage()));
	}

	protected function getPermissionValue(CommandSender $sender, string $node) : Tristate{
		if($sender->hasPermission($node)){
			return Tristate::TRUE();
		}elseif($sender->isPermissionSet($node)){
			return Tristate::FALSE();
		}else{
			return Tristate::UNDEFINED();
		}
	}

	protected function hasPermission(CommandSender $sender, string $node) : bool{
		return $sender->hasPermission($node);
	}

	protected function performCommand(CommandSender $sender, string $command) : void{
		$this->owningPlugin->getServer()->dispatchCommand($sender, $command);
	}

	protected function isConsole(CommandSender $sender) : bool{
		return $sender instanceof ConsoleCommandSender;
	}
}
