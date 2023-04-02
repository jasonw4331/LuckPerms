<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\graph;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static Order POST_ORDER()
 * @method static Order PRE_ORDER()
 */
final class Order{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("PRE_ORDER"),
			new self("POST_ORDER")
		);
	}

	private function __construct(string $name){
		$this->Enum___construct($name);
	}
}
