<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

abstract class SuffixNodeBuilder extends ChatMetaNodeBuilder{

	abstract function suffix(string $suffix) : SuffixNodeBuilder;

}
