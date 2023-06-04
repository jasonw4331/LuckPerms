<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\node\NodeType;
use jasonw4331\LuckPerms\api\node\ScopedNode;

abstract class RegexPermissionNode extends ScopedNode{

	public function getType() : NodeType{
		return NodeType::REGEX_PERMISSION();
	}

	abstract function getPatternString() : string;

	public static function builder(?string $pattern = null) : RegexPermissionNodeBuilder{
		if($pattern === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forRegexPermission();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forRegexPermission()->pattern($pattern);
	}
}
