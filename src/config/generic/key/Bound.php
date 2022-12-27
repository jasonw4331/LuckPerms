<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic\key;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;

/**
 * @template T
 */
class Bound {
	/**
	 * @var callable
	 */
	private $factory;

	/**
	 * @param callable(ConfigurationAdapter,string,T):T $factory
	 * @param string           $path
	 * @param T                $def
	 */
	public function __construct(callable $factory, private string $path, private mixed $def) {
		$this->factory = $factory;
	}

	/**
	 * @param ConfigurationAdapter $adapter
	 *
	 * @return T
	 */
	public function __invoke(ConfigurationAdapter $adapter) : mixed {
		return \Closure::fromCallable($this->factory)->call($adapter, $this->path, $this->def);
	}
}