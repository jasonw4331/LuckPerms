<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\context\calculator;

use jasonwynn10\LuckPerms\api\context\Context;
use jasonwynn10\LuckPerms\api\context\ContextConsumer;

class EmptyWorldNameRewriter extends WorldNameRewriter{

	public function __construct(){ }

	public function rewriteAndSubmit(string $worldName, ContextConsumer $consumer) : void{
		if(Context::isValidValue($worldName)){
			$consumer->accept(DefaultContextKeys::WORLD_KEY(), $worldName);
		}
	}
}
