<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\types;

use jasonwynn10\LuckPerms\api\node\ChatMetaType;
use jasonwynn10\LuckPerms\api\node\ScopedNode;

abstract class ChatMetaNode extends ScopedNode {
	abstract function getPriority() : int;

	abstract function getMetaValue() : string;

	abstract function getMetaType() : ChatMetaType;

}