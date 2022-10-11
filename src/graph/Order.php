<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\graph;

use pocketmine\utils\EnumTrait;

/**
 * @generate-registry-docblock
 */
final class Order{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void {
		self::registerAll(
			new self("PRE_ORDER"),
			new self("POST_ORDER")
		);
	}

	private function __construct(string $name) {
		$this->Enum___construct($name);
	}
}