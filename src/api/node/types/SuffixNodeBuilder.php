<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

abstract class SuffixNodeBuilder extends ChatMetaNodeBuilder{

	abstract function suffix(string $suffix) : SuffixNodeBuilder;

}
