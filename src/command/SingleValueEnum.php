<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\command;

use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;

class SingleValueEnum extends StringEnumArgument{

	public function __construct(string $name) {
		parent::__construct($name, false);
	}

	public function parse(string $argument, CommandSender $sender) : string{
		return $argument;
	}

	public function getTypeName() : string{
		return $this->name;
	}

	public function getValue(string $string) : string{
		return $string;
	}

	public function getEnumValues(): array {
		return [$this->name];
	}
}