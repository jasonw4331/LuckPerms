<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\verbose\event;

use jasonwynn10\LuckPerms\api\query\QueryOptions;
use jasonwynn10\LuckPerms\cacheddata\result\StringResult;
use jasonwynn10\LuckPerms\node\utils\NodeJsonSerializer;
use jasonwynn10\LuckPerms\verbose\VerboseCheckTarget;

class MetaCheckEvent extends VerboseEvent {

	public function __construct(CheckOrigin $origin, VerboseCheckTarget $checkTarget, QueryOptions $checkQueryOptions, int $time, \Exception $trace, string $thread, private string $key, private StringResult $result){ }

	public function getKey() : string{
		return $this->key;
	}

	public function getResult() : StringResult{
		return $this->result;
	}

	public function getType() : string{
		return VerboseEventType::META();
	}

	protected function serializeTo(\stdClass $object) : void{
		$object->key = $this->key;

		$object->result = $this->result->result();
		if($this->result !== StringResult::nullResult()) {
			$object->resultInfo = $this->serializeResult($this->result);
		}
	}

	private function serializeResult(StringResult $result) : \stdClass{
		$object = new \stdClass();
		$object->result = (string) $result->result();

		if($result->node() !== null) {
			$object->node = NodeJsonSerializer::serializeNode($result->node(), true);
		}

		if($result->overriddenResult() !== null) {
			$overridden = [];

			$next = $result->overriddenResult();
			while($next !== null) {
				$overridden[] = $this->serializeResult($next);
				$next = $next->overriddenResult();
			}

			$object->overridden = $overridden;
		}

		return $object;
	}

	public function eval(string $variable) : bool {
		return $variable === "meta" ||
			\mb_strtolower($this->getCheckTarget()->description()) === \mb_strtolower($variable) ||
			\str_starts_with(\mb_strtolower($this->getKey()), \mb_strtolower($variable)) ||
			\mb_strtolower($this->getResult()->result()) === \mb_strtolower($variable);
	}
}