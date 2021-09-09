<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\graph;

use Ramsey\Collection\DoubleEndedQueue;

final class DepthFirstIterator extends AbstractIterator {
	private Graph $graph;

	private DoubleEndedQueue $stack;
	private array $visited = [];
	private Order $order;

	/**
	 * @param Graph $graph
	 * @param       $startNode
	 */
	public function __construct(Graph $graph, $root, Order $order){
		$this->graph = $graph;
		$this->stack = new DoubleEndedQueue($root::class, [$this->withSuccessors($root)]);
		$this->order = $order;
	}

	protected function computerNext() {
		while(true) {
			if($this->stack->isEmpty()) {
				return $this->endOfData();
			}
			/** @var NodeAndSuccessors $node */
			$node = $this->stack->firstElement();
			$firstVisit = in_array($node->node, $this->visited);
			$lastVisit = !$node->successorIterator->hasNext();
			$produceNode = ($firstVisit and $this->order === Order::PRE_ORDER()) or ($lastVisit and $this->order === Order::POST_ORDER());
			if($lastVisit) {
				$this->stack->removeLast();
			}else{
				$successor = $node->successorIterator->next();
				if(!in_array($successor, $this->visited)) {
					$this->stack->addFirst($this->withSuccessors($successor));
				}
			}
			if($produceNode) {
				return $node->node;
			}
		}
	}

	private function withSuccessors($node) : NodeAndSuccessors {
		return new NodeAndSuccessors($node, $this->graph->successors($node));
	}
}