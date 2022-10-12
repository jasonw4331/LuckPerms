<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\model\group\Group;
use jasonwynn10\LuckPerms\api\node\NodeType;
use jasonwynn10\LuckPerms\api\node\ScopedNode;

abstract class InheritanceNode extends ScopedNode{

	public function getType() : NodeType{
		return NodeType::INHERITANCE();
	}

	abstract function getGroupName() : string;

	public static function builder(string|Group|null $group = null) : InheritanceNodeBuilder {
		if($group === null) {
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forInheritance();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forInheritance()->group($group);
	}
}