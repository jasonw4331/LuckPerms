<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\sender;

use jasonwynn10\LuckPerms\api\util\Tristate;
use jasonwynn10\LuckPerms\commands\generic\permission\CommandPermission;
use jasonwynn10\LuckPerms\LuckPerms;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Sender{

	const CONSOLE_UUID = Uuid::NIL;

	const CONSOLE_NAME = "CONSOLE";

	abstract function getPlugin() : LuckPerms;

	abstract function getName() : string;

	public function getNameWithLocation() : string{
		$name = $this->getName();

		$contextManager = $this->getPlugin()->getContextManager();

		$staticContext = $contextManager->getStaticContext();

		if($staticContext->isEmpty()){
			return $name;
		}elseif($staticContext->size() === 1){
			$location = $staticContext->iterator()->next();
		}else{
			$servers = $staticContext->getValues(DefaultContextKeys::SERVER_KEY);
			if($servers->size() === 1){
				$location = $servers->iterator()->next();
			}else{
				$location = \implode(';', \array_map(static fn($pair) => $pair->getKey() . "=" . $pair->getValue(), $staticContext->toSet()));
			}
		}

		return $name . "@" . $location;
	}

	abstract function getUniqueId() : UuidInterface;

	abstract function sendMessage(string $message) : void;

	abstract function getPermissionValue(string $permission) : Tristate;

	abstract function hasPermission(string $permission) : bool;

	public function hasCommandPermission(CommandPermission $permission) : bool{
		return $this->hasPermission($permission->getPermission());
	}

	abstract function performCommand(string $commandLine) : void;

	abstract function isConsole() : bool;

	public function isValid() : bool{
		return true;
	}

}
