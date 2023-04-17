<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms;

use jasonwynn10\LuckPerms\api\util\Tristate;
use jasonwynn10\LuckPerms\locale\TranslationManager;
use jasonwynn10\LuckPerms\sender\AbstractSender;
use jasonwynn10\LuckPerms\sender\Sender;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class SenderFactory{

	public function __construct(private LuckPerms $plugin){ }

	public function getUniqueID(CommandSender $sender) : UuidInterface{
		if($sender instanceof Player){
			return $sender->getUniqueId();
		}
		return Uuid::fromString(Sender::CONSOLE_UUID);
	}

	public function getName(CommandSender $sender) : string{
		if($sender instanceof Player){
			return $sender->getName();
		}
		return Sender::CONSOLE_NAME;
	}

	public function sendMessage(CommandSender $sender, Translatable|string $message) : void{
		$sender->sendMessage(TranslationManager::render($message, $sender->getLanguage()));
	}

	public function getPermissionValue(CommandSender $sender, string $node) : Tristate{
		if($sender->hasPermission($node)){
			return Tristate::TRUE();
		}elseif($sender->isPermissionSet($node)){
			return Tristate::FALSE();
		}else{
			return Tristate::UNDEFINED();
		}
	}

	public function hasPermission(CommandSender $sender, string $node) : bool{
		return $sender->hasPermission($node);
	}

	public function performCommand(CommandSender $sender, string $command) : void{
		$this->plugin->getServer()->dispatchCommand($sender, $command);
	}

	public function isConsole(CommandSender $sender) : bool{
		return $sender instanceof ConsoleCommandSender;
	}

	public function wrap(CommandSender $sender) : Sender{
		return new AbstractSender($this->plugin, $this, $sender);
	}
}
