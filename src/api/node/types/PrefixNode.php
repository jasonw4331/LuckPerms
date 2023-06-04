<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\LuckPermsProvider;
use jasonwynn10\LuckPerms\api\node\NodeType;

abstract class PrefixNode extends ChatMetaNode{

	public function getType() : NodeType{
		return NodeType::PREFIX();
	}

	public static function builder(?string $prefix = null, ?int $priority = null) : PrefixNodeBuilder{
		if($prefix === null && $priority === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forPrefix();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forPrefix()->prefix($prefix)->priority($priority);
	}
}
