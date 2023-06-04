<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\context\calculator;

use jasonw4331\LuckPerms\api\context\Context;
use jasonw4331\LuckPerms\api\context\ContextConsumer;
use Ramsey\Collection\Map\TypedMap;
use Ramsey\Collection\Set;
use function mb_strtolower;

class NonEmptyWorldNameRewriter extends WorldNameRewriter{

	/**
	 * @param TypedMap<string, string> $rewrites
	 */
	public function __construct(private TypedMap $rewrites){ }

	public function rewriteAndSubmit(string $worldName, ContextConsumer $consumer) : void{
		$seen = new Set('string', []);
		$worldName = mb_strtolower($worldName);
		while(Context::isValidValue($worldName) && $seen->add($worldName)){
			$consumer->accept(DefaultContextKeys::WORLD_KEY(), $worldName);
			$worldName = $this->rewrites->get($worldName);
			if($worldName === null){
				break;
			}
		}
	}
}
