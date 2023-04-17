<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\sender;

use jasonwynn10\LuckPerms\api\util\Tristate;
use jasonwynn10\LuckPerms\LuckPerms;
use jasonwynn10\LuckPerms\SenderFactory;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Ramsey\Uuid\UuidInterface;

/**
 * @template Tvalue of CommandSender
 */
final class AbstractSender extends Sender{
	private UuidInterface $uniqueId;
	private string $name;
	private bool $isConsole;

	/**
	 * @param LuckPerms     $plugin
	 * @param SenderFactory $senderFactory
	 * @param Tvalue        $sender
	 */
	public function __construct(private LuckPerms $plugin, private SenderFactory $factory, private CommandSender $sender){
		$this->uniqueId = $this->factory->getUniqueID($sender);
		$this->name = $this->factory->getName($sender);
		$this->isConsole = $this->factory->isConsole($sender);
	}

	public function getPlugin() : LuckPerms{
		return $this->plugin;
	}

	public function getUniqueId() : UuidInterface{
		return $this->uniqueId;
	}

	public function getName() : string{
		return $this->name;
	}

	public function sendMessage(string $message) : void{
		$this->factory->sendMessage($this->sender, $message);
	}

	public function getPermissionValue(string $permission) : Tristate{
		return $this->isConsole ? Tristate::TRUE() : $this->factory->getPermissionValue($this->sender, $permission);
	}

	public function hasPermission(string $permission) : bool{
		return $this->isConsole || $this->factory->hasPermission($this->sender, $permission);
	}

	public function performCommand(string $commandLine) : void{
		$this->factory->performCommand($this->sender, $commandLine);
	}

	public function isConsole() : bool{
		return $this->isConsole;
	}

	public function isValid() : bool{
		return $this->isConsole || (Server::getInstance()->getPlayerByUUID($this->uniqueId)?->isOnline() ?? false);
	}

}
