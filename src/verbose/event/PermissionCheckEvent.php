<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\verbose\event;

use jasonw4331\LuckPerms\api\query\QueryOptions;
use jasonw4331\LuckPerms\cacheddata\result\TristateResult;
use jasonw4331\LuckPerms\node\utils\NodeJsonSerializer;
use jasonw4331\LuckPerms\verbose\VerboseCheckTarget;
use function json_encode;
use function mb_strtolower;
use function str_starts_with;

class PermissionCheckEvent extends VerboseEvent{

	public function __construct(CheckOrigin $origin, VerboseCheckTarget $checkTarget, QueryOptions $checkQueryOptions, int $checkTime, \Throwable $checkTrace, string $checkThread, private string $permission, private TristateResult $result){
		parent::__construct($origin, $checkTarget, $checkQueryOptions, $checkTime, $checkTrace, $checkThread);
	}

	public function getPermission() : string{
		return $this->permission;
	}

	public function getResult() : TristateResult{
		return $this->result;
	}

	public function getType() : VerboseEventType{
		return VerboseEventType::PERMISSION();
	}

	protected function serializeTo(\stdClass $object) : void{
		$object->permission = $this->permission;
		$object->result = mb_strtolower($this->result->name());
		if($this->result !== TristateResult::UNDEFINED()){
			$object->resultInfo = $this->serializeResult($this->result);
		}
	}

	private static function serializeResult(TristateResult $result) : \stdClass{
		$object = new \stdClass();

		$object->result = mb_strtolower($result->name());

		if($result->processorClass() !== null){
			$object->processorClass = $result->processorClass();
		}

		if($result->node() !== null){
			$object->node = NodeJsonSerializer::serializeNode($result->node(), true);
		}

		if($result->overriddenResult() !== null){
			$overridden = [];

			$next = $result->overriddenResult();
			while($next !== null){
				$overridden[] = json_encode(self::serializeResult($next));
				$next = $next->overriddenResult();
			}

			$object->overridden = $overridden;
		}

		return $object;
	}

	public function eval(string $variable) : bool{
		return $variable === 'permission' ||
			mb_strtolower($this->getCheckTarget()->describe()) === mb_strtolower($variable) ||
			str_starts_with(mb_strtolower($this->getPermission()), mb_strtolower($variable)) ||
			mb_strtolower($this->getResult()->name()) === mb_strtolower($variable);
	}

}
