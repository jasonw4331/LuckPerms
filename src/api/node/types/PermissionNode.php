<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeType;
use jasonwynn10\LuckPerms\api\node\ScopedNode;

abstract class PermissionNode extends ScopedNode{

	function getType() : NodeType{
		return NodeType::PERMISSION();
	}

	abstract function getPermission() : string;

	abstract function isWildcard() : bool;

	abstract function getWildcardLevel() : int;

	public static function builder(?string $permission = null) : PermissionNodeBuilder {
		if($permission === null) {
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forPermission();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forPermission()->permission($permission);
	}
}