<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\model\group\Group;
use jasonwynn10\LuckPerms\api\node\NodeBuilder;

abstract class InheritanceNodeBuilder extends NodeBuilder{

	abstract function group(string|Group $group) : InheritanceNodeBuilder;

}