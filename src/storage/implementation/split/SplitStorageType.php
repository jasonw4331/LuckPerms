<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\storage\implementation\split;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static SplitStorageType GROUP()
 * @method static SplitStorageType LOG()
 * @method static SplitStorageType TRACK()
 * @method static SplitStorageType USER()
 * @method static SplitStorageType UUID()
 */
class SplitStorageType{
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("LOG"),
			new self("USER"),
			new self("GROUP"),
			new self("TRACK"),
			new self("UUID")
		);
	}
}
