<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeType;
use jasonwynn10\LuckPerms\api\node\ScopedNode;
use function is_string;

abstract class WeightNode extends ScopedNode{

	public function getType() : NodeType{
		return NodeType::WEIGHT();
	}

	abstract function getWeight() : int;

	public static function builder(string|int|null $weight = null) : WeightNodeBuilder{
		if($weight === null){
			return LuckPermsProvider::get()->getNodeBuilderRegistry()->forWeight();
		}elseif(is_string($weight)){
			throw new \InvalidArgumentException("Weight must be an integer, got string");
		}
		return LuckPermsProvider::get()->getNodeBuilderRegistry()->forWeight()->weight($weight);
	}

}
