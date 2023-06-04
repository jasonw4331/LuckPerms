<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\config\generic\key;

use jasonw4331\LuckPerms\config\generic\adapter\ConfigurationAdapter;
use pocketmine\utils\EnumTrait;
use Ramsey\Collection\Map\AbstractTypedMap;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static ConfigKeyFactory BOOLEAN()
 * @method static ConfigKeyFactory LOWERCASE_STRING()
 * @method static ConfigKeyFactory STRING()
 * @method static ConfigKeyFactory STRING_MAP()
 */

/**
 * @template T
 */
final class ConfigKeyFactory{
	use EnumTrait {
		__construct as Enum___construct;
	}

	private function __construct(string $name, private string $functionName){
		$this->Enum___construct($name);
	}

	protected static function setup() : void{
		self::registerAll(
			new self("boolean", "getBoolean"),
			new self("string", "getString"),
			new self("lowercase_string", "getLowercaseString"),
			new self("string_map", "getStringMap"),
		);
	}

	/**
	 * @param callable(ConfigurationAdapter):T $function
	 *
	 * @return SimpleConfigKey<T>
	 */
	public static function key(callable $function) : SimpleConfigKey{
		return new SimpleConfigKey($function);
	}

	public static function notReloadable(SimpleConfigKey $key) : SimpleConfigKey{
		$key->setReloadable(false);
		return $key;
	}

	/**
	 * @return SimpleConfigKey<bool>
	 */
	public static function booleanKey(string $path, bool $def) : SimpleConfigKey{
		return self::key(new Bound(self::BOOLEAN(), $path, $def));
	}

	/**
	 * @return SimpleConfigKey<string>
	 */
	public static function stringKey(string $path, ?string $def) : SimpleConfigKey{
		return self::key(new Bound(self::STRING(), $path, $def));
	}

	/**
	 * @return SimpleConfigKey<string>
	 */
	public static function lowercaseStringKey(string $path, string $def) : SimpleConfigKey{
		return self::key(new Bound(self::LOWERCASE_STRING(), $path, $def));
	}

	/**
	 * @return SimpleConfigKey<AbstractTypedMap>
	 */
	public static function mapKey(string $path) : SimpleConfigKey{
		return self::key(new Bound(self::STRING_MAP(), $path, null));
	}

	public function getFunctionName() : string{
		return $this->functionName;
	}
}
