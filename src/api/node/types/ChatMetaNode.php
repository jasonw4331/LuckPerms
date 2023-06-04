<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\node\ChatMetaType;
use jasonw4331\LuckPerms\api\node\ScopedNode;

abstract class ChatMetaNode extends ScopedNode{
	abstract function getPriority() : int;

	abstract function getMetaValue() : string;

	abstract function getMetaType() : ChatMetaType;

}
