<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\api\model\data;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see \pocketmine\utils\RegistryUtils::_generateMethodAnnotations()
 *
 * @method static TemporaryNodeMergeStrategy ADD_NEW_DURATION_TO_EXISTING()
 * @method static TemporaryNodeMergeStrategy NONE()
 * @method static TemporaryNodeMergeStrategy REPLACE_EXISTING_IF_DURATION_LONGER()
 */
class TemporaryNodeMergeStrategy{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void {
		self::registerAll(
			new self("ADD_NEW_DURATION_TO_EXISTING"), // Expiry durations will be added to the existing expiry time of a permission.
			new self("REPLACE_EXISTING_IF_DURATION_LONGER"), // Expiry durations will be replaced if the new duration is longer than the current one.
			new self("NONE") // The operation will fail if an existing temporary node is present.
		);
	}

	private function __construct(string $name) {
		$this->Enum___construct($name);
	}

}