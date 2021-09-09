<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\query;

use pocketmine\utils\CloningRegistryTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see \pocketmine\utils\RegistryUtils::_generateMethodAnnotations()
 *
 * @method static int ALL_FLAGS()
 * @method static Flag[] ALL_FLAGS_SET()
 * @method static int ALL_FLAGS_SIZE()
 */
final class FlagUtils{
	use CloningRegistryTrait;
	private function __construct(){}

	protected static function register(string $name, $member) : void {
		self::_registryRegister($name, (object) $member);
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

	protected static function setup() : void {
		self::register("ALL_FLAGS_SET", Flag::getAll());
		self::register("ALL_FLAGS_SIZE", count(Flag::getAll()));
		self::register("ALL_FLAGS", self::toByte0(Flag::getAll()));
	}

	public static function read(int $b, Flag $setting) : bool {
		return ($b >> $setting->ordinal() & 1) == 1; // TODO: ordinal PR for PocketMine
	}

	/**
	 * @param Flag[] $settings
	 *
	 * @return int
	 */
	public static function toByte(array $settings) : int {
		if(count($settings) === self::ALL_FLAGS_SIZE()) {
			return self::ALL_FLAGS();
		}
		return self::toByte0($settings);
	}

	/**
	 * @param Flag[] $settings
	 *
	 * @return int
	 */
	private static function toByte0(array $settings) : int {
		$b = 0;
		foreach($settings as $setting) {
			$b |= 1 << $setting->ordinal();
		}
		return $b;
	}

	public static function toSet(int $b) : array {
		$settings = Flag::getAll();
		foreach($settings as $setting) {
			if(self::read($b, $setting)) {
				$settings[] = $setting;
			}
		}
		return $settings;
	}
}