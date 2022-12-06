<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node\metadata;

final class SimpleNodeMetadataKey extends NodeMetadataKey {
	private string $name;
	private object $type;

	/**
	 * @param string $name
	 * @param object $type
	 */
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

	public function equals(object $o) : bool {
		if($this === $o) {
			return true;
		}
		if($o == null or get_class($this) !== get_class($o)) {
			return false;
		}
		return $this->name === $o->name and $this->type === $o->type;
	}
}