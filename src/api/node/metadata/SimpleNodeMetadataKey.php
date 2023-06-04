<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\metadata;

use function get_class;
use function mb_strtolower;

final class SimpleNodeMetadataKey extends NodeMetadataKey{
	private string $name;
	private object $type;

	public function __construct(string $name, object $type){
		$this->name = mb_strtolower($name);
		$this->type = $type;
	}

	public function name() : string{
		return $this->name;
	}

	public function type() : object{
		return $this->type;
	}

	public function __toString() : string{
		return "NodeMetadataKey(name=" . $this->name . ", type=" . $this->type . ")";
	}

	public function equals(object $o) : bool{
		if($this === $o){
			return true;
		}
		if($o == null || get_class($this) !== get_class($o)){
			return false;
		}
		return $this->name === $o->name && $this->type === $o->type;
	}
}
