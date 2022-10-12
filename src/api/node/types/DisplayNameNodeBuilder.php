<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeBuilder;

abstract class DisplayNameNodeBuilder extends NodeBuilder{

	abstract public function displayName(string $displayName) : DisplayNameNodeBuilder;

}