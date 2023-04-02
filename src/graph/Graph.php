<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\graph;

abstract class Graph{

	public abstract function successors($node) : \Traversable;

	public function traverse(TraversalAlgorithm $algorithm, $startNode) : \Traversable{
		return $algorithm->traverse($this, $startNode);
	}

}
