<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\graph;

final class NodeAndSuccessors {
	public $node;
	public \Iterator $successorIterator;

	public function __construct($node, \Traversable $successors) {
		$this->node = $node;
		$this->successorIterator = new \IteratorIterator($successors);
	}
}