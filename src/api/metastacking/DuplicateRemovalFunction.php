<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\api\metastacking;

use pocketmine\utils\EnumTrait;
use pocketmine\utils\Utils;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see \pocketmine\utils\RegistryUtils::_generateMethodAnnotations()
 *
 * @method static DuplicateRemovalFunction FIRST_ONLY()
 * @method static DuplicateRemovalFunction LAST_ONLY()
 * @method static DuplicateRemovalFunction RETAIN_ALL()
 */
final class DuplicateRemovalFunction{
	use EnumTrait {
		__construct as Enum___construct;
	}

	private $func;

	protected static function setup() : void {
		self::registerAll(
			new self("RETAIN_ALL", function(array &$list) : void {}),
			new self("FIRST_ONLY", function(array &$list) : void {
				$seen = new \SplFixedArray(count($list)-1);
				foreach($list as $key => $item) {
					try{
						$seen[] = $item;
					}catch(\Exception $e) {
						unset($item[$key]);
					}
				}
			}),
			new self("LAST_ONLY", function(array &$list) : void {
				$seen = new \SplFixedArray(count($list)-1);
				foreach(array_reverse($list, true) as $key => $item) {
					try{
						$seen[] = $item;
					}catch(\Exception $e) {
						unset($item[$key]);
					}
				}
			})
		);
	}

	private function __construct(string $name, callable $func) {
		$this->Enum___construct($name);
		Utils::validateCallableSignature(function(array &$list) : void {}, $func);
		$this->func = $func;
	}
}