<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\metastacking;

use jasonw4331\LuckPerms\api\node\ChatMetaType;
use jasonw4331\LuckPerms\api\node\types\ChatMetaNode;

interface MetaStackElement{
	public function shouldAccumulate(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) : bool;
}
