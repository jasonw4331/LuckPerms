<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\cacheddata\type;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see \pocketmine\utils\RegistryUtils::_generateMethodAnnotations()
 *
 * @method static Strategy HIGHEST_NUMBER()
 * @method static Strategy INHERITANCE()
 * @method static Strategy LOWEST_NUMBER()
 */
abstract class Strategy{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void {
		self::registerAll(
			new class("INHERITANCE") extends Strategy {
				public function select(array $values) : string{
					return $values[0];
				}
			},
			new class("HIGHEST_NUMBER") extends Strategy {
				public function select(array $values) : string{
					return SimpleMetaValueSelector::selectNumber($values, new class() implements DoubleSelectionPredicate{
						public function shouldSelect(float $value, float $current) : bool{
							return $value > $current;
						}
					});
				}
			},
			new class("LOWEST_NUMBER") extends Strategy {
				public function select(array $values) : string{
					SimpleMetaValueSelector::selectNumber($values, new class() implements DoubleSelectionPredicate{
						public function shouldSelect(float $value, float $current) : bool{
							return $value < $current;
						}
					});
				}
			}
		);
	}

	private function __construct(string $name) {
		$this->Enum___construct($name);
	}

	public abstract function select(array $values) : string;

	public static function parse(string $s) : ?self {
		$func_name = strtoupper(str_replace('-', '_', $s));
		return self::$$func_name(); // TODO: return dynamic function
	}
}