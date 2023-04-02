<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\metadata;

abstract class NodeMetadataKey{

	public static function of(string $name, object $type) : NodeMetadataKey{
		return new SimpleNodeMetadataKey($name, $type);
	}

	abstract function name() : string;

	abstract function type() : object;

}
