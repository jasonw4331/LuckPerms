<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\query;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static QueryMode CONTEXTUAL()
 * @method static QueryMode NON_CONTEXTUAL()
 */
final class QueryMode{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("CONTEXTUAL"),
			new self("NON_CONTEXTUAL"),
		);
	}

	private function __construct(string $name){
		$this->Enum___construct($name);
	}
}