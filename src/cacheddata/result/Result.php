<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\cacheddata\result;

use jasonwynn10\LuckPerms\api\node\Node;

/**
 * @template T
 * @template N of Node
 */
interface Result{

	/**
	 * @phpstan-return T|null
	 */
	function result() : mixed;

	/**
	 * @phpstan-return N|null
	 */
	function node() : ?Node;

}