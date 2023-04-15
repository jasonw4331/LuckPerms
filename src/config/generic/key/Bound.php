<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic\key;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;

/**
 * @template Tvalue
 */
class Bound{

	/**
	 * @param ConfigKeyFactory<Tvalue> $factory
	 * @param Tvalue                   $def
	 */
	public function __construct(private ConfigKeyFactory $factory, private string $path, private mixed $def){ }

	/**
	 * @return Tvalue
	 */
	public function __invoke(ConfigurationAdapter $adapter) : mixed{
		return $adapter::{$this->factory->getFunctionName()}($this->path, $this->def);
	}
}
