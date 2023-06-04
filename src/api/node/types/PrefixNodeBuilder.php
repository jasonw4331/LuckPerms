<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

abstract class PrefixNodeBuilder extends ChatMetaNodeBuilder{

	abstract function prefix(string $prefix) : PrefixNodeBuilder;

}
