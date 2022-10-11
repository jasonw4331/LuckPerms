<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\scheduler;

abstract class SchedulerAdapter{

	abstract function async() : Executor;

	abstract function sync() : Executor;

	function executeAsync(\Closure $closure) : void {
		$this->async()->execute($closure);
	}

	function executeSync(\Closure $closure) : void {
		$this->sync()->execute($closure);
	}

	abstract function asyncLater(\Closure $closure, int $delay) : SchedulerTask;

	abstract function asyncRepeating(\Closure $closure, int $interval) : SchedulerTask;

	abstract function shutdownScheduler() : void;

	abstract function shutdownExecutor() : void;

}