<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\util\traits;

use function count;
use function mb_strtoupper;
use function preg_match;

/**
 * @template T
 */
trait MixedRegistryTrait{
	/**
	 * @var T[]
	 * @phpstan-var array<string, T>
	 */
	private static $members = null;

	private static function verifyName(string $name) : void{
		if(preg_match('/^(?!\d)[A-Za-z\d_]+$/u', $name) === 0){
			throw new \InvalidArgumentException("Invalid member name \"$name\", should only contain letters, numbers and underscores, and must not start with a number");
		}
	}

	/**
	 * Adds the given object to the registry.
	 *
	 * @param T $member
	 *
	 * @throws \InvalidArgumentException
	 */
	private static function _registryRegister(string $name, mixed $member) : void{
		self::verifyName($name);
		$upperName = mb_strtoupper($name);
		if(isset(self::$members[$upperName])){
			throw new \InvalidArgumentException("\"$upperName\" is already reserved");
		}
		self::$members[$upperName] = $member;
	}

	/**
	 * Inserts default entries into the registry.
	 *
	 * (This ought to be private, but traits suck too much for that.)
	 */
	abstract protected static function setup() : void;

	/**
	 * @throws \InvalidArgumentException
	 * @internal Lazy-inits the enum if necessary.
	 */
	protected static function checkInit() : void{
		if(self::$members === null){
			self::$members = [];
			self::setup();
		}
	}

	/**
	 * @return T
	 * @throws \InvalidArgumentException
	 */
	private static function _registryFromString(string $name) : mixed{
		self::checkInit();
		$upperName = mb_strtoupper($name);
		if(!isset(self::$members[$upperName])){
			throw new \InvalidArgumentException("No such registry member: " . self::class . "::" . $upperName);
		}
		return self::$members[$upperName];
	}

	/**
	 * @param string  $name
	 * @param mixed[] $arguments
	 *
	 * @phpstan-param list<mixed> $arguments
	 *
	 * @return T
	 */
	public static function __callStatic($name, $arguments){
		if(count($arguments) > 0){
			throw new \ArgumentCountError("Expected exactly 0 arguments, " . count($arguments) . " passed");
		}
		try{
			return self::_registryFromString($name);
		}catch(\InvalidArgumentException $e){
			throw new \Error($e->getMessage(), 0, $e);
		}
	}

	/**
	 * @return T[]
	 * @phpstan-return array<string, T>
	 */
	private static function _registryGetAll() : array{
		self::checkInit();
		return self::$members;
	}
}
