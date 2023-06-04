<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node;

use jasonw4331\LuckPerms\api\node\types\ChatMetaNode;
use jasonw4331\LuckPerms\api\node\types\DisplayNameNode;
use jasonw4331\LuckPerms\api\node\types\InheritanceNode;
use jasonw4331\LuckPerms\api\node\types\MetaNode;
use jasonw4331\LuckPerms\api\node\types\PermissionNode;
use jasonw4331\LuckPerms\api\node\types\PrefixNode;
use jasonw4331\LuckPerms\api\node\types\RegexPermissionNode;
use jasonw4331\LuckPerms\api\node\types\SuffixNode;
use jasonw4331\LuckPerms\api\node\types\WeightNode;
use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static SimpleNodeType CHAT_META()
 * @method static SimpleNodeType DISPLAY_NAME()
 * @method static SimpleNodeType INHERITANCE()
 * @method static SimpleNodeType META()
 * @method static SimpleNodeType META_OR_CHAT_META()
 * @method static SimpleNodeType PERMISSION()
 * @method static SimpleNodeType PREFIX()
 * @method static SimpleNodeType REGEX_PERMISSION()
 * @method static SimpleNodeType SUFFIX()
 * @method static SimpleNodeType WEIGHT()
 */
abstract class NodeType{
	use EnumTrait {
		__construct as Enum___construct;
	}

	/**
	 * @inheritDoc
	 */
	protected static function setup() : void{
		self::registerAll(
			new SimpleNodeType("PERMISSION", static fn(Node $n) : bool => $n instanceof PermissionNode),
			new SimpleNodeType("REGEX_PERMISSION", static fn(Node $n) : bool => $n instanceof RegexPermissionNode),
			new SimpleNodeType("INHERITANCE", static fn(Node $n) : bool => $n instanceof InheritanceNode),
			new SimpleNodeType("PREFIX", static fn(Node $n) : bool => $n instanceof PrefixNode),
			new SimpleNodeType("SUFFIX", static fn(Node $n) : bool => $n instanceof SuffixNode),
			new SimpleNodeType("META", static fn(Node $n) : bool => $n instanceof MetaNode),
			new SimpleNodeType("WEIGHT", static fn(Node $n) : bool => $n instanceof WeightNode),
			new SimpleNodeType("DISPLAY_NAME", static fn(Node $n) : bool => $n instanceof DisplayNameNode),
			new SimpleNodeType("CHAT_META", static fn(Node $n) : bool => $n instanceof ChatMetaNode),
			new SimpleNodeType("META_OR_CHAT_META", fn(Node $n) : bool => $this->META()->matches($n) || $this->CHAT_META()->matches($n)),
		);
	}

	public function __construct(string $name){
		$this->Enum___construct($name);
	}

	abstract public function matches(Node $node) : bool;

	public function predicate(?callable $and = null) : callable{
		if($and === null){
			return [$this, "matches"];
		}
		return fn($node) => $this->matches($node) && $and($node);
	}
}
