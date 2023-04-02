<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeBuilder;

abstract class MetaNodeBuilder extends NodeBuilder{

	abstract function key(string $key) : MetaNodeBuilder;

	abstract function value(bool|string $value) : MetaNodeBuilder;

}
