<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\node\NodeBuilder;

abstract class DisplayNameNodeBuilder extends NodeBuilder{

	abstract public function displayName(string $displayName) : DisplayNameNodeBuilder;

}
