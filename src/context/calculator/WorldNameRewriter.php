<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\context\calculator;

use jasonwynn10\LuckPerms\api\context\ContextConsumer;
use Ramsey\Collection\Map\TypedMap;

abstract class WorldNameRewriter{

	/**
	 * @param TypedMap<string, string> $rewrites
	 */
	public static function of(TypedMap $rewrites) : WorldNameRewriter{
		if(\count($rewrites) < 1){
			return new EmptyWorldNameRewriter();
		}
		return new NonEmptyWorldNameRewriter($rewrites);
	}

	abstract public function rewriteAndSubmit(string $worldName, ContextConsumer $consumer) : void;
}
