<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node;

abstract class ScopedNode extends Node{

	abstract function getType() : NodeType;

	abstract function toBuilder() : NodeBuilder;

}
