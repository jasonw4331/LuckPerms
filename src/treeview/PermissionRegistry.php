<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\treeview;

use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\scheduler\TaskScheduler;
use Ramsey\Collection\Queue;

class PermissionRegistry{

	private TreeNode $rootNode;
	/** @var Queue<string> $queue */
	private Queue $queue;
	private TaskHandler $task;

	public function __construct(TaskScheduler $scheduler){
		$this->rootNode = new TreeNode();
		$this->queue = new Queue("string");
		$this->task = $scheduler->scheduleRepeatingTask(new ClosureTask(\Closure::fromCallable([$this, 'tick'])), 20); // tick every second
	}

	public function getRootNode() : TreeNode{
		return $this->rootNode;
	}

	public function rootAsList() : array{
		return \array_values($this->rootNode->makeImmutableCopy()->getNodeEndings());
	}

	public function offer(string $permission) : void{
		$this->queue->offer($permission);
	}

	private function tick() : void{
		for($e = null; ($e = $this->queue->poll()) !== null;){
			$this->insert($e);
		}
	}

	public function close() : void{
		$this->task->cancel();
	}

	public function insert(string $permission) : void{
		$permission = \mb_strtolower($permission);

		// split the permission up into parts
		$parts = \explode(".", $permission);

		// insert the permission into the node structure
		$current = $this->rootNode;
		foreach($parts as $part){
			$current = $current->tryInsert($part);
			if($current === null){
				return;
			}
		}
	}

}
