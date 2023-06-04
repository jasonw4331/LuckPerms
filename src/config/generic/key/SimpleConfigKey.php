<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\config\generic\key;

use jasonw4331\LuckPerms\config\generic\adapter\ConfigurationAdapter;

/**
 * @template T
 * @extends ConfigKey<T>
 */
class SimpleConfigKey implements ConfigKey{
	/**
	 * @var callable                                 $function
	 * @phpstan-var callable(ConfigurationAdapter):T $function
	 */
	private $function;

	private int $ordinal = -1;
	private bool $reloadable = true;

	/**
	 * @phpstan-param callable(ConfigurationAdapter):T $function
	 */
	public function __construct(callable $function){
		$this->function = $function;
	}

	/**
	 * @phpstan-return T
	 */
	public function get(ConfigurationAdapter $adapter) : mixed{
		return ($this->function)($adapter);
	}

	public function ordinal() : int{
		return $this->ordinal;
	}

	public function reloadable() : bool{
		return $this->reloadable;
	}

	public function setOrdinal(int $ordinal) : void{
		$this->ordinal = $ordinal;
	}

	public function setReloadable(bool $reloadable) : void{
		$this->reloadable = $reloadable;
	}
}
