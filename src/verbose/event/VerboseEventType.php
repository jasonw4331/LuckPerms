<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\verbose\event;

use pocketmine\utils\EnumTrait;
use function mb_strtolower;

/**
 * @generate-registry-docblock
 */
class VerboseEventType{
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("PERMISSION"), // {@link PermissionCheckEvent}
			new self("META") // {@link MetaCheckEvent}
		);
	}

	public function __toString() : string{
		return mb_strtolower($this->name());
	}
}
