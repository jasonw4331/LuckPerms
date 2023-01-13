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
 * @method static Flag APPLY_INHERITANCE_NODES_WITHOUT_SERVER_CONTEXT()
 * @method static Flag APPLY_INHERITANCE_NODES_WITHOUT_WORLD_CONTEXT()
 * @method static Flag INCLUDE_NODES_WITHOUT_SERVER_CONTEXT()
 * @method static Flag INCLUDE_NODES_WITHOUT_WORLD_CONTEXT()
 * @method static Flag RESOLVE_INHERITANCE()
 */
final class Flag{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("RESOLVE_INHERITANCE", 0), // If parent groups should be resolved
			new self("INCLUDE_NODES_WITHOUT_SERVER_CONTEXT", 1), // If global or non-server-specific nodes should be applied
			new self("INCLUDE_NODES_WITHOUT_WORLD_CONTEXT", 2), // If global or non-world-specific nodes should be applied
			new self("APPLY_INHERITANCE_NODES_WITHOUT_SERVER_CONTEXT", 3), // If global or non-server-specific group memberships should be applied
			new self("APPLY_INHERITANCE_NODES_WITHOUT_WORLD_CONTEXT", 4) // If global or non-world-specific group memberships should be applied
		);
	}

	private function __construct(string $name, private int $ordinal){
		$this->Enum___construct($name);
	}

	public function ordinal() : int{
		return $this->ordinal;
	}
}