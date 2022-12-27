<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config\generic\adapter;

use jasonwynn10\LuckPerms\LuckPerms;
use Ramsey\Collection\Map\AbstractTypedMap;

interface ConfigurationAdapter{
	public function getPlugin() : LuckPerms;
	public function reload() : void;
	public function getString(string $path, ?string $def) : string;
	public function getInteger(string $path, int $def) : int;
	public function getBoolean(string $path, bool $def) : bool;
	/**
	 * @param string $path
	 * @param List<string>  $def
	 *
	 * @return List<string>
	 */
	public function getStringList(string $path, array $def) : array;

	/**
	 * @param string $path
	 * @param AbstractTypedMap<string, string> $def
	 *
	 * @return AbstractTypedMap<string, string>
	 */
	public function getStringMap(string $path, AbstractTypedMap $def) : AbstractTypedMap;
}