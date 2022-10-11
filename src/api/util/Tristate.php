<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\util;
use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see \pocketmine\utils\RegistryUtils::_generateMethodAnnotations()
 * @generate-registry-docblock
 *
 * @method static Tristate TRUE()
 * @method static Tristate FALSE()
 * @method static Tristate UNDEFINED()
 */
final class Tristate {
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void {
		self::registerAll(
			new self("true",true),
			new self("false",false),
			new self("undefined",false)
		);
	}

	private bool $booleanValue;

	private function __construct(string $name, bool $booleanValue) {
		$this->Enum___construct($name);
		$this->booleanValue = $booleanValue;
	}

	public function of(bool|null $value) : Tristate {
		return $value === null ? Tristate::UNDEFINED() : ($value ? Tristate::TRUE() : Tristate::FALSE());
	}

	public function asBoolean() : bool {
		return $this->booleanValue;
	}

}