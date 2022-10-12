<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\node;

final class SimpleNodeType extends NodeType {

	/** @var callable $matches */
	private $matches;

	public function __construct(string $name, callable $matches) {
		parent::__construct($name);
		$this->matches = $matches;
	}

	public function matches(Node $node) : bool {
		return ($this->matches)($node);
	}

	public function __toString() : string {
		return $this->name();
	}

}