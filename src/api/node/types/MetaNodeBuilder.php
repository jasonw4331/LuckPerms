<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\node\NodeBuilder;

abstract class MetaNodeBuilder extends NodeBuilder{

	abstract function key(string $key) : MetaNodeBuilder;

	abstract function value(bool|string $value) : MetaNodeBuilder;

}
