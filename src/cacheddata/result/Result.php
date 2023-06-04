<?php
declare(strict_types=1);

namespace jasonw4331\LuckPerms\cacheddata\result;

use jasonw4331\LuckPerms\api\node\Node;

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