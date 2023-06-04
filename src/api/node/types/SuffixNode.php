<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeType;

abstract class SuffixNode extends ChatMetaNode{
	public function getType() : NodeType{
		return NodeType::SUFFIX();
	}

	public static function builder(?string $suffix = null, ?int $priority = null) : SuffixNodeBuilder{
		if($suffix === null && $priority === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forSuffix();
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forSuffix()->suffix($suffix)->priority($priority);
	}
}
