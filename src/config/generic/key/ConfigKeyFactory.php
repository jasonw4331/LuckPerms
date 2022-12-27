<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic\key;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;
use jasonwynn10\LuckPerms\util\traits\ExtraRegistryTrait;
use Ramsey\Collection\Map\AbstractTypedMap;

/**
 * @template T
 *
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 * @method static callable BOOLEAN()
 * @method static callable STRING()
 * @method static callable LOWERCASE_STRING()
 * @method static callable STRING_MAP()
 */
class ConfigKeyFactory{
	use ExtraRegistryTrait{
		_registryRegister as register;
	}

	private function __construct(){
		//NOOP
	}

	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var callable[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		self::register("boolean", [ConfigurationAdapter::class, "getBoolean"]);
		self::register("string", [ConfigurationAdapter::class, "getString"]);
		self::register("lowercase_string", static fn(ConfigurationAdapter $adapter, string $path, string $def) => mb_strtolower($adapter->getString($path, $def)));
		self::register("string_map", static fn(ConfigurationAdapter $config, string $path, AbstractTypedMap $def) => clone $config->getStringMap($path, $def));
	}

	/**
	 * @param callable(ConfigurationAdapter):T $function
	 *
	 * @return SimpleConfigKey
	 */
	public static function key(callable $function) : SimpleConfigKey {
		return new SimpleConfigKey($function);
	}

	public static function notReloadable(SimpleConfigKey $key) : SimpleConfigKey {
		$key->setReloadable(false);
		return $key;
	}

	/**
	 * @param string $path
	 * @param bool   $def
	 *
	 * @return SimpleConfigKey<bool>
	 */
	public static function booleanKey(string $path, bool $def) : SimpleConfigKey {
		return self::key(new Bound(self::BOOLEAN(), $path, $def));
	}

	/**
	 * @param string $path
	 * @param string $def
	 *
	 * @return SimpleConfigKey<string>
	 */
	public static function stringKey(string $path, string $def) : SimpleConfigKey {
		return self::key(new Bound(self::STRING(), $path, $def));
	}

	/**
	 * @param string $path
	 * @param string $def
	 *
	 * @return SimpleConfigKey<string>
	 */
	public static function lowercaseStringKey(string $path, string $def) : SimpleConfigKey {
		return self::key(new Bound(self::LOWERCASE_STRING(), $path, $def));
	}

	/**
	 * @param string $path
	 *
	 * @return SimpleConfigKey<AbstractTypedMap>
	 */
	public static function mapKey(string $path) : SimpleConfigKey {
		return self::key(new Bound(self::STRING_MAP(), $path, null));
	}
}