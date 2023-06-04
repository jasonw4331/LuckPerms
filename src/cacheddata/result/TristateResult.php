<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\cacheddata\result;

use jasonw4331\LuckPerms\api\node\Node;
use jasonw4331\LuckPerms\api\util\Tristate;
use jasonw4331\LuckPerms\calculator\processor\PermissionProcessor;
use pocketmine\utils\EnumTrait;
use pocketmine\utils\Utils;

/**
 * @generate-registry-docblock
 *
 * @method static TristateResult TRUE()
 * @method static TristateResult FALSE()
 * @method static TristateResult UNDEFINED()
 */
class TristateResult implements \JsonSerializable{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self(Tristate::TRUE(), null, null),
			new self(Tristate::FALSE(), null, null),
			new self(Tristate::UNDEFINED(), null, null)
		);
	}

	private ?TristateResult $overriddenResult = null;

	public function __construct(private Tristate $result, private ?Node $node, private ?PermissionProcessor $processorClass){
		$this->Enum___construct($result->name());
	}

	public function result() : Tristate{
		return $this->result;
	}

	public function node() : ?Node{
		return $this->node;
	}

	public function processorClass() : ?PermissionProcessor{
		return $this->processorClass;
	}

	public function processorClassFriendly() : ?string{
		return Utils::getNiceClassName($this->processorClass);
	}

	public function overriddenResult() : ?TristateResult{
		return $this->overriddenResult;
	}

	public function setOverriddenResult(?TristateResult $overriddenResult) : void{
		$this->overriddenResult = $overriddenResult;
	}

	public function toString() : string{
		return "TristateResult(result=" . $this->result . ", node=" . $this->node . ", processorClass=" . $this->processorClassFriendly() . ", overriddenResult=" . $this->overriddenResult . ")";
	}

	public function jsonSerialize(){
		// TODO: Implement jsonSerialize() method.
	}
}
