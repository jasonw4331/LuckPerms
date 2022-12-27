<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\config\generic\key;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;

/**
 * @template T
 */
interface ConfigKey{
	/**
	 * Gets the position of this key within the keys enum.
	 *
	 * @return int the position
	 */
	public function ordinal() : int;
	/**
	 * Gets if the config key can be reloaded.
	 *
	 * @return bool the if the key can be reloaded
	 */
	public function reloadable() : bool;
	/**
	 * Resolves and returns the value mapped to this key using the given config instance.
	 *
	 * @param ConfigurationAdapter $adapter the config adapter instance
	 *
	 * @return mixed the value mapped to this key
	 * @phpstan-return T
	 */
	public function get(ConfigurationAdapter $adapter) : mixed;
}