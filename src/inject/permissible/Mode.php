<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\inject\permissible;

use pocketmine\utils\EnumTrait;

/**
 * @generate-registry-docblock
 */
final class Mode{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void {
		self::registerAll(
			new self("INJECT"),
			new self("UNINJECT")
		);
	}

	private function __construct(string $name) {
		$this->Enum___construct($name);
	}
}