<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\graph;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static TraversalAlgorithm BREADTH_FIRST()
 * @method static TraversalAlgorithm DEPTH_FIRST_POST_ORDER()
 * @method static TraversalAlgorithm DEPTH_FIRST_PRE_ORDER()
 */
abstract class TraversalAlgorithm{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new class("BREADTH_FIRST") extends TraversalAlgorithm{
				public function traverse(Graph $graph, $startNode) : \Traversable{
					return new BreadthFirstIterator($graph, $startNode);
				}
			},
			new class("DEPTH_FIRST_PRE_ORDER") extends TraversalAlgorithm{
				public function traverse(Graph $graph, $startNode) : \Traversable{
					return new DepthFirstIterator($graph, $startNode, Order::PRE_ORDER());
				}
			},
			new class("DEPTH_FIRST_POST_ORDER") extends TraversalAlgorithm{
				public function traverse(Graph $graph, $startNode) : \Traversable{
					return new DepthFirstIterator($graph, $startNode, Order::POST_ORDER());
				}
			}
		);
	}

	private function __construct(string $name){
		$this->Enum___construct($name);
	}

	public abstract function traverse(Graph $graph, $startNode) : \Traversable;
}
