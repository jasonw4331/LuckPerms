<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\graph;

use Ramsey\Collection\DoubleEndedQueue;
use function in_array;

final class DepthFirstIterator extends AbstractIterator{
	private Graph $graph;

	private DoubleEndedQueue $stack;
	private array $visited = [];
	private Order $order;

	public function __construct(Graph $graph, $root, Order $order){
		$this->graph = $graph;
		$this->stack = new DoubleEndedQueue($root::class, [$this->withSuccessors($root)]);
		$this->order = $order;
	}

	protected function computerNext(){
		while(true){
			if($this->stack->isEmpty()){
				return $this->endOfData();
			}
			/** @var NodeAndSuccessors $node */
			$node = $this->stack->firstElement();
			$firstVisit = in_array($node->node, $this->visited, true);
			$lastVisit = !$node->successorIterator->hasNext();
			$produceNode = ($firstVisit && $this->order === Order::PRE_ORDER()) || ($lastVisit && $this->order === Order::POST_ORDER());
			if($lastVisit){
				$this->stack->removeLast();
			}else{
				$successor = $node->successorIterator->next();
				if(!in_array($successor, $this->visited, true)){
					$this->stack->addFirst($this->withSuccessors($successor));
				}
			}
			if($produceNode){
				return $node->node;
			}
		}
	}

	private function withSuccessors($node) : NodeAndSuccessors{
		return new NodeAndSuccessors($node, $this->graph->successors($node));
	}
}
