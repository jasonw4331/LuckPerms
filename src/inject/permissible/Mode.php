<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\inject\permissible;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static Mode INJECT()
 * @method static Mode UNINJECT()
 */
final class Mode{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("INJECT"),
			new self("UNINJECT")
		);
	}

	private function __construct(string $name){
		$this->Enum___construct($name);
	}
}
