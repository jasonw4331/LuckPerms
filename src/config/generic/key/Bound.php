<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic\key;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;

/**
 * @template T
 */
class Bound {

	/**
	 * @param ConfigKeyFactory<T> $factory
	 * @param string $path
	 * @param T      $def
	 */
	public function __construct(private ConfigKeyFactory $factory, private string $path, private mixed $def) {}

	/**
	 * @param ConfigurationAdapter $adapter
	 *
	 * @return T
	 */
	public function __invoke(ConfigurationAdapter $adapter) : mixed {
		return $adapter::{$this->factory->getFunctionName()}($this->path, $this->def);
	}
}