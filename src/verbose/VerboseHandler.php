<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\verbose;

use jasonwynn10\LuckPerms\api\query\QueryOptions;
use jasonwynn10\LuckPerms\scheduler\SchedulerAdapter;
use jasonwynn10\LuckPerms\scheduler\SchedulerTask;
use jasonwynn10\LuckPerms\sender\Sender;
use Ramsey\Collection\Queue;
use Ramsey\Collection\Set;

final class VerboseHandler{

	/** @var Set<VerboseListener> $listeners */
	private Set $listeners;
	/** @var Queue<VerboseEvent> $queue */
	private Queue $queue;
	private bool $listening = false;

	private SchedulerTask $task;

	public function __construct(SchedulerAdapter $scheduler){
		$this->listeners = new Set(VerboseListener::class);
		$this->queue = new Queue(VerboseEvent::class);
		$this->task = $scheduler->asyncRepeating(\Closure::fromCallable([$this, 'tick']), 100);
	}

	public function offerPermissionCheckEvent(CheckOrigin $origin, VerboseCheckTarget $checkTarget, QueryOptions $checkQueryOptions, string $permission, TristateResult $result) : void{
		// don't bother even processing the check if there are no listeners registered
		if(!$this->listening){
			return;
		}

		$time = \microtime(true);
		$trace = new \Exception();
		$thread = \Thread::getCurrentThreadId();

		// add the check data to a queue to be processed later.
		$this->queue->offer(new PermissionCheckEvent($origin, $checkTarget, $checkQueryOptions, $time, $trace, $thread, $permission, $result));
	}

	public function offerMetaCheckEvent(CheckOrigin $origin, VerboseCheckTarget $checkTarget, QueryOptions $checkQueryOptions, string $key, string $result) : void{
		// don't bother even processing the check if there are no listeners registered
		if(!$this->listening){
			return;
		}

		$time = \microtime(true);
		$trace = new \Exception();
		$thread = \Thread::getCurrentThreadId();

		// add the check data to a queue to be processed later.
		$this->queue->offer(new MetaCheckEvent($origin, $checkTarget, $checkQueryOptions, $time, $trace, $thread, $key, $result));
	}

	public function registerListener(Sender $sender, VerboseFilter $filter, bool $notify) : void{
		// flush out anything before this listener was added
		$this->flush();

		$this->listeners->offsetSet($sender->getUniqueId(), new VerboseListener($sender, $filter, $notify));
		$this->listening = true;
	}

	public function unregisterListener(Sender $sender) : VerboseListener{
		// flush out anything before this listener was removed
		$this->flush();

		$listener = $this->listeners->offsetGet($sender->getUniqueId());
		$this->listeners->offsetUnset($sender->getUniqueId());
		return $listener;
	}

	private function tick() : void{
		// remove listeners where the sender is no longer valid
		/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
		$this->listeners = $this->listeners->filter(static fn(VerboseListener $listener) => !$listener->getNotifiedSender()->isValid());

		// handle all events in the queue
		$this->flush();

		// update listening state
		$this->listening = !$this->listeners->isEmpty();
	}

	public function flush() : void{
		for($e = null; ($e = $this->queue->poll()) !== null;){
			foreach($this->listeners as $listener){
				$listener->acceptEvent($e);
			}
		}
	}

	public function close() : void{
		$this->task->cancel();
	}

}
