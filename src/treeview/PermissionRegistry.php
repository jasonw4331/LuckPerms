<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\treeview;

use jasonwynn10\LuckPerms\scheduler\SchedulerAdapter;
use jasonwynn10\LuckPerms\scheduler\SchedulerTask;
use Ramsey\Collection\Queue;

class PermissionRegistry{

	private TreeNode $rootNode;
	/** @var Queue<string> $queue */
	private Queue $queue;
	private SchedulerTask $task;

	public function __construct(SchedulerAdapter $scheduler) {
		$this->rootNode = new TreeNode();
		$this->queue = new Queue("string");
		$this->task = $scheduler->asyncRepeating(\Closure::fromCallable([$this, 'tick']), 100);
	}

	/**
	 * @return TreeNode
	 */
	public function getRootNode() : TreeNode{
		return $this->rootNode;
	}

	public function rootAsList() : array {
		return array_values($this->rootNode->makeImmutableCopy()->getNodeEndings());
	}

	public function offer(string $permission) : void {
		$this->queue->offer($permission);
	}

	private function tick() : void {
		for($e = null; ($e = $this->queue->poll()) !== null; ) {
			$this->insert($e);
		}
	}

	public function close() : void {
		$this->task->cancel();
	}

	public function insert(string $permission) : void {
		$permission = mb_strtolower($permission);

		// split the permission up into parts
		$parts = explode(".", $permission);

		// insert the permission into the node structure
		$current = $this->rootNode;
		foreach($parts as $part) {
			$current = $current->tryInsert($part);
			if($current === null) {
				return;
			}
		}
	}

}