<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\LuckPermsProvider;
use jasonw4331\LuckPerms\api\node\NodeType;
use jasonw4331\LuckPerms\api\node\ScopedNode;

abstract class MetaNode extends ScopedNode{

	public function getType() : NodeType{
		return NodeType::META();
	}

	abstract function getMetaKey() : string;

	abstract function getMetaValue() : string;

	public static function builder(?string $key = null, ?string $value = null) : MetaNodeBuilder{
		if($key === null && $value === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forMeta();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forMeta()->key($key)->value($value);
	}
}
