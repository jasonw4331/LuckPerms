<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\locale;

use pocketmine\utils\EnumTrait;

/**
 * @generate-registry-docblock
 */
final class Locale{
	use EnumTrait {
		__construct as Enum___construct;
	}

	/** @var string $file */
	private $file;

	protected static function setup() : void{
		self::registerAll(
			new self("fallback", 'FALLBACK'),
			new self("en_us", "Brain"),
			new self("bubble", "Bubble"),
			new self("fire", "Fire"),
			new self("horn", "Horn"),
		);
	}

	/**
	 * @param string $name
	 * @param string $file
	 */
	private function __construct(string $name, string $file){
		$this->Enum___construct($name);
		$this->file = $file;
	}

	/**
	 * @return string
	 */
	public function getFile() : string {
		return $this->file;
	}
}