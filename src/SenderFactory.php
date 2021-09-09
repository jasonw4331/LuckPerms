<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SenderFactory{

	private LuckPerms $plugin;

	public function __construct(LuckPerms $plugin){
		$this->plugin = $plugin;
	}

	protected function getName(CommandSender $sender) : string {
		return $sender->getName();
	}

	protected function getUniqueID(CommandSender $sender) : UuidInterface {
		if($sender instanceof Player) {
			return $sender->getUniqueId();
		}
		return Uuid::fromString(Uuid::NIL);
	}

	protected function sendMessage(CommandSender $sender, Translatable|string $message) {
		$locale = null;
		$locale = $sender->getLanguage();
		$sender->sendMessage($message);
	}
}