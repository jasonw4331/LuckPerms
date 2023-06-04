<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node\types;

use jasonw4331\LuckPerms\api\node\NodeBuilder;

abstract class ChatMetaNodeBuilder extends NodeBuilder{

	abstract function priority(?int $priority) : ChatMetaNodeBuilder;

}
