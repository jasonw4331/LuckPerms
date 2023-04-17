<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\verbose\event;

use pocketmine\utils\EnumTrait;

class CheckOrigin{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("PLATFORM_API"), // Indicates the check was caused by a lookup in a platform API
			new self("PLATFORM_API_HAS_PERMISSION"), // Indicates the check was caused by a 'hasPermission' check on the platform
			new self("PLATFORM_API_HAS_PERMISSION_SET"), // Indicates the check was caused by a 'hasPermissionSet' type check on the platform
			new self("THIRD_PARTY_API"), // Indicates the check was caused by a 3rd party API call
			new self("LUCKPERMS_API"), // Indicates the check was caused by a LuckPerms API call
			new self("INTERNAL") // Indicates the check was caused by a LuckPerms internal
		);
	}
}
