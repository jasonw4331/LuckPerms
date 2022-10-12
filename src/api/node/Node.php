<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node;

use jasonwynn10\LuckPerms\api\LuckPermsProvider;
use jasonwynn10\LuckPerms\api\node\metadata\NodeMetadataKey;
use jasonwynn10\LuckPerms\context\ImmutableContextSet;

abstract class Node{

	public static function builder(string $key) : NodeBuilder{
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forKey($key);
	}

	abstract function getType() : NodeType;

	abstract function getKey() : string;

	abstract function getValue() : string;

	public function isNegated() : bool{
		return !$this->getValue();
	}

	abstract function resolveShorthand() : array;

	abstract function hasExpiry() : bool;

	abstract function getExpiry() : int;

	abstract function hasExpired() : bool;

	abstract function getExpiryDuration() : int;

	abstract function getContexts() : ImmutableContextSet;

	abstract function getMetadata(NodeMetadataKey $key) : mixed;

	public function metadata(NodeMetadataKey $key) : mixed {
		return $this->getMetadata($key) ?? throw new \InvalidArgumentException("Node '" . $this->getKey() . "' does not have '" . $key->name() . "' attached.");
	}

	abstract function equals(Node $other, callable $equalityPredicate) : bool;

	abstract function toBuilder() : NodeBuilder;

}