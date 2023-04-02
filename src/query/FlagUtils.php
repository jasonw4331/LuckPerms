<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\query;

use jasonwynn10\LuckPerms\util\traits\MixedRegistryTrait;
use Ramsey\Collection\Set;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static int ALL_FLAGS()
 * @method static Set ALL_FLAGS_SET()
 * @method static int ALL_FLAGS_SIZE()
 */
final class FlagUtils{
	use MixedRegistryTrait;

	private function __construct(){ }

	protected static function register(string $name, mixed $member) : void{
		self::_registryRegister($name, $member);
	}

	/**
	 * @return mixed[]
	 */
	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var mixed[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		self::register("ALL_FLAGS_SET", new Set(Flag::class, Flag::getAll()));
		self::register("ALL_FLAGS_SIZE", self::ALL_FLAGS_SET()->count());
		self::register("ALL_FLAGS", self::toByte0(self::ALL_FLAGS_SET()));
	}

	public static function read(int $b, Flag $setting) : bool{
		return ($b >> $setting->ordinal() & 1) == 1; // TODO: ordinal PR for PocketMine
	}

	/**
	 * @param Set<Flag> $settings
	 */
	public static function toByte(Set $settings) : int{
		if($settings->count() === self::ALL_FLAGS_SIZE()){
			return self::ALL_FLAGS();
		}
		return self::toByte0($settings);
	}

	/**
	 * @param Set<Flag> $settings
	 */
	private static function toByte0(Set $settings) : int{
		$b = 0;
		foreach($settings as $setting){
			$b |= 1 << $setting->ordinal();
		}
		return $b;
	}

	public static function toSet(int $b) : Set{
		$settings = new Set(Flag::class, Flag::getAll());
		foreach($settings as $setting){
			if(self::read($b, $setting)){
				$settings->add($setting);
			}
		}
		return $settings;
	}
}
