<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\NodeBuilder;

abstract class WeightNodeBuilder extends NodeBuilder{

	abstract function weight(int $weight) : WeightNodeBuilder;

}