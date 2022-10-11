<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\api\context;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static ContextSatisfyMode ALL_VALUES_PER_KEY()
 * @method static ContextSatisfyMode AT_LEAST_ONE_VALUE_PER_KEY()
 */
final class ContextSatisfyMode{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void {
		self::registerAll(
			new self("AT_LEAST_ONE_VALUE_PER_KEY"),
			new self("ALL_VALUES_PER_KEY")
		);
	}

	private function __construct(string $name) {
		$this->Enum___construct($name);
	}
}