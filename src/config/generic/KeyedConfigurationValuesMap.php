<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic;

use jasonwynn10\LuckPerms\config\generic\key\ConfigKey;
use Ramsey\Collection\Map\TypedMap;
use function array_fill;

/**
 * @template Tvalue of object
 */
class KeyedConfigurationValuesMap{

	/** @var TypedMap<int, Tvalue> $values */
	private TypedMap $values;

	public function __construct(int $size){
		$this->values = new TypedMap('int', 'object', array_fill(0, $size, null));
	}

	/**
	 * @phpstan-param ConfigKey<Tvalue> $key
	 * @phpstan-return Tvalue
	 */
	public function get(ConfigKey $key) : object{
		return $this->values->offsetGet($key->ordinal());
	}

	/**
	 * @phpstan-param ConfigKey<Tvalue> $key
	 */
	public function put(ConfigKey $key, object $value) : void{
		$this->values->put($key->ordinal(), $value);
	}
}
