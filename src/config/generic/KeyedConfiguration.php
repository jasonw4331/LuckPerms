<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;
use jasonwynn10\LuckPerms\config\generic\key\ConfigKey;
use jasonwynn10\LuckPerms\config\generic\key\SimpleConfigKey;
use pocketmine\utils\EnumTrait;
use function array_filter;
use function count;

class KeyedConfiguration{
	private KeyedConfigurationValuesMap $values;

	public function __construct(private ConfigurationAdapter $adapter, private array $keys){
		$this->values = new KeyedConfigurationValuesMap(count($keys));
	}

	protected function init() : void{
		$this->load(true);
	}

	/**
	 * Gets the value of a given context key.
	 *
	 * @param ConfigKey<T> $key the key
	 *
	 * @return T|null the value mapped to the given key. May be null.
	 * @template T
	 */
	public function get(ConfigKey $key){
		return $this->values->get($key);
	}

	protected function load(bool $initial) : void{
		foreach($this->keys as $key){
			if($initial || $key->reloadable()){
				$this->values->put($key, $key->get($this->adapter));
			}
		}
	}

	/**
	 * Reloads the configuration.
	 */
	public function reload() : void{
		$this->adapter->reload();
		$this->load(false);
	}

	/**
	 * Initializes the given pseudo-enum keys class.
	 *
	 * @param class-string<EnumTrait> $keysClass the keys class
	 *
	 * @return SimpleConfigKey[] the list of keys defined by the class with their ordinal values set
	 */
	public static function initialise(string $keysClass) : array{
		// get a list of all keys
		/** @var SimpleConfigKey[] $keys */
		$keys = array_filter(
			$keysClass::getAll(),
			fn($var) => $keysClass::$var() instanceof SimpleConfigKey
		);

		// set ordinal values
		$i = 0;
		foreach($keys as $key){
			$key->setOrdinal($i++);
		}

		return $keys;
	}
}
