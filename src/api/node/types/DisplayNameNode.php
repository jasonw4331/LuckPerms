<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeType;
use jasonwynn10\LuckPerms\api\node\ScopedNode;

abstract class DisplayNameNode extends ScopedNode{

	public function getType() : NodeType{
		return NodeType::DISPLAY_NAME();
	}

	abstract public function getDisplayName() : string;

	public static function builder(?string $displayName = null) : DisplayNameNodeBuilder{
		if($displayName === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forDisplayName();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forDisplayName()->displayName($displayName);
	}

}
