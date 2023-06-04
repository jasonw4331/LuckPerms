<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\verbose\event;

use jasonw4331\LuckPerms\api\query\QueryOptions;
use jasonw4331\LuckPerms\verbose\VerboseCheckTarget;

abstract class VerboseEvent{
	protected function __construct(private CheckOrigin $origin, private VerboseCheckTarget $checkTarget, private QueryOptions $checkQueryOptions, private int $checkTime, private \Throwable $checkTrace, private string $checkThread){ }

	/**
	 * @return CheckOrigin
	 */
	public function getOrigin() : CheckOrigin{
		return $this->origin;
	}

	/**
	 * @return VerboseCheckTarget
	 */
	public function getCheckTarget() : VerboseCheckTarget{
		return $this->checkTarget;
	}

	/**
	 * @return QueryOptions
	 */
	public function getCheckQueryOptions() : QueryOptions{
		return $this->checkQueryOptions;
	}

	/**
	 * @return int
	 */
	public function getCheckTime() : int{
		return $this->checkTime;
	}

	/**
	 * @return \Throwable
	 */
	public function getCheckTrace() : \Throwable{
		return $this->checkTrace;
	}

	/**
	 * @return string
	 */
	public function getCheckThread() : string{
		return $this->checkThread;
	}

	protected abstract function getType() : VerboseEventType;

	protected abstract function serializeTo(\stdClass $object) : void;

}
