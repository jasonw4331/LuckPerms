<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\verbose;

use jasonwynn10\LuckPerms\api\query\QueryOptions;
use jasonwynn10\LuckPerms\cacheddata\result\StringResult;
use jasonwynn10\LuckPerms\cacheddata\result\TristateResult;
use jasonwynn10\LuckPerms\sender\Sender;
use jasonwynn10\LuckPerms\verbose\event\CheckOrigin;
use jasonwynn10\LuckPerms\verbose\event\MetaCheckEvent;
use jasonwynn10\LuckPerms\verbose\event\PermissionCheckEvent;
use jasonwynn10\LuckPerms\verbose\event\VerboseEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\scheduler\TaskScheduler;
use Ramsey\Collection\Queue;
use Ramsey\Collection\Set;
use function microtime;

final class VerboseHandler{

	/** @var Set<VerboseListener> $listeners */
	private Set $listeners;
	/** @var Queue<VerboseEvent> $queue */
	private Queue $queue;
	private bool $listening = false;

	private TaskHandler $task;

	public function __construct(TaskScheduler $scheduler){
		$this->listeners = new Set(VerboseListener::class);
		$this->queue = new Queue(VerboseEvent::class);
		$this->task = $scheduler->scheduleRepeatingTask(new ClosureTask(\Closure::fromCallable([$this, 'tick'])), 2); // 100 milliseconds if at 20 TPS
	}

	/**
	 * Offers permission check data to the handler, to be eventually passed onto listeners.
	 *
	 * <p>The check data is added to a queue to be processed later, to avoid blocking
	 * the main thread each time a permission check is made.</p>
	 *
	 * @param CheckOrigin        $origin the origin of the check
	 * @param VerboseCheckTarget $checkTarget the target of the permission check
	 * @param QueryOptions       $checkQueryOptions the query options used for the check
	 * @param string             $permission the permission which was checked for
	 * @param TristateResult     $result the result of the permission check
	 */
	public function offerPermissionCheckEvent(CheckOrigin $origin, VerboseCheckTarget $checkTarget, QueryOptions $checkQueryOptions, string $permission, TristateResult $result) : void{
		// don't bother even processing the check if there are no listeners registered
		if(!$this->listening){
			return;
		}

		$time = (int) microtime(true);
		$trace = new \Exception();
		$thread = (string) \Thread::getCurrentThreadId();

		// add the check data to a queue to be processed later.
		$this->queue->offer(new PermissionCheckEvent($origin, $checkTarget, $checkQueryOptions, $time, $trace, $thread, $permission, $result));
	}

	/**
	 * Offers meta check data to the handler, to be eventually passed onto listeners.
	 *
	 * <p>The check data is added to a queue to be processed later, to avoid blocking
	 * the main thread each time a meta check is made.</p>
	 *
	 * @param CheckOrigin        $origin the origin of the check
	 * @param VerboseCheckTarget $checkTarget the target of the meta check
	 * @param QueryOptions       $checkQueryOptions the query options used for the check
	 * @param string             $key the meta key which was checked for
	 * @param StringResult             $result the result of the meta check
	 */
	public function offerMetaCheckEvent(CheckOrigin $origin, VerboseCheckTarget $checkTarget, QueryOptions $checkQueryOptions, string $key, StringResult $result) : void{
		// don't bother even processing the check if there are no listeners registered
		if(!$this->listening){
			return;
		}

		$time = (int) microtime(true);
		$trace = new \Exception();
		$thread = (string) \Thread::getCurrentThreadId();

		// add the check data to a queue to be processed later.
		$this->queue->offer(new MetaCheckEvent($origin, $checkTarget, $checkQueryOptions, $time, $trace, $thread, $key, $result));
	}

	/**
	 * Registers a new listener for the given player.
	 *
	 * @param Sender        $sender the sender to notify, if notify is true
	 * @param VerboseFilter $filter the filter string
	 * @param bool          $notify if the sender should be notified in chat on each check
	 */
	public function registerListener(Sender $sender, VerboseFilter $filter, bool $notify) : void{
		// flush out anything before this listener was added
		$this->flush();

		$this->listeners->offsetSet($sender->getUniqueId()->toString(), new VerboseListener($sender, $filter, $notify));
		$this->listening = true;
	}

	/**
	 * Removes a listener for a given player
	 *
	 * @param Sender $sender the sender
	 *
	 * @return VerboseListener the existing listener, if one was actually registered
	 */
	public function unregisterListener(Sender $sender) : VerboseListener{
		// flush out anything before this listener was removed
		$this->flush();

		$listener = $this->listeners->offsetGet($sender->getUniqueId()->toString());
		$this->listeners->offsetUnset($sender->getUniqueId()->toString());
		return $listener;
	}

	private function tick() : void{
		// remove listeners where the sender is no longer valid
		/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
		$this->listeners = $this->listeners->filter(static fn(VerboseListener $l) => !$l->getNotifiedSender()->isValid());

		// handle all events in the queue
		$this->flush();

		// update listening state
		$this->listening = !$this->listeners->isEmpty();
	}

	/**
	 * Flushes the pending events to listeners.
	 */
	public function flush() : void{
		for($e = null; ($e = $this->queue->poll()) !== null;){
			/** @var VerboseListener $listener */
			foreach($this->listeners as $listener){
				$listener->acceptEvent($e);
			}
		}
	}

	public function close() : void{
		$this->task->cancel();
	}

}
