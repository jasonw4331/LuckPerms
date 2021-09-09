<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\api\metastacking;

use jasonwynn10\LuckPerms\api\node\ChatMetaType;

interface MetaStackElement {
	public function shouldAccumulate(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) : bool;
}