<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node;

use jasonwynn10\LuckPerms\api\node\types\ChatMetaNodeBuilder;
use jasonwynn10\LuckPerms\api\node\types\PrefixNode;
use jasonwynn10\LuckPerms\api\node\types\SuffixNode;
use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static ChatMetaType PREFIX()
 * @method static ChatMetaType SUFFIX()
 */
abstract class ChatMetaType{
	use EnumTrait {
		__construct as Enum___construct;
	}

	/**
	 * @inheritDoc
	 */
	protected static function setup() : void{
		self::registerAll(
			new class("PREFIX", NodeType::PREFIX()) extends ChatMetaType{
				public function builder(?string $value = null, ?int $priority = null) : ChatMetaNodeBuilder{
					return PrefixNode::builder($value, $priority);
				}
			},
			new class("SUFFIX", NodeType::SUFFIX()) extends ChatMetaType{
				public function builder(?string $value = null, ?int $priority = null) : ChatMetaNodeBuilder{
					return SuffixNode::builder($value, $priority);
				}
			},
		);
	}

	private NodeType $nodeType;

	public function __construct(string $name, NodeType $nodeType){
		$this->Enum___construct($name);
		$this->nodeType = $nodeType;
	}

	public function nodeType() : NodeType{
		return $this->nodeType;
	}

	public abstract function builder(?string $value = null, ?int $priority = null) : ChatMetaNodeBuilder;

	public function __toString() : string{
		return $this->name();
	}
}
