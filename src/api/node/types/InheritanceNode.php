<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\model\group\Group;
use jasonw4331\LuckPerms\api\node\NodeType;
use jasonw4331\LuckPerms\api\node\ScopedNode;

abstract class InheritanceNode extends ScopedNode{

	public function getType() : NodeType{
		return NodeType::INHERITANCE();
	}

	abstract function getGroupName() : string;

	public static function builder(string|Group|null $group = null) : InheritanceNodeBuilder{
		if($group === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forInheritance();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forInheritance()->group($group);
	}
}
