<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node;

abstract class ScopedNode extends Node{

	abstract function getType() : NodeType;

	abstract function toBuilder() : NodeBuilder;

}
