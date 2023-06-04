<?php
declare(strict_types=1);

namespace jasonw4331\LuckPerms\cacheddata\result;

use jasonw4331\LuckPerms\api\node\Node;
use jasonw4331\LuckPerms\api\util\Tristate;
use jasonw4331\LuckPerms\calculator\processor\PermissionProcessor;

class TristateResultFactory{

	public function __construct(private PermissionProcessor $processorClass){}

	public function result(null|Node|Tristate $result) {
		if($result instanceof Node) {
			return new TristateResult(Tristate::of($result->getValue()), $result, $this->processorClass);
		}
		return match ($result) {
			TristateResult::TRUE(), TristateResult::FALSE() => new TristateResult($result, null, $this->processorClass),
			null, TristateResult::UNDEFINED() => clone TristateResult::UNDEFINED(),
			default => throw new \InvalidArgumentException("Invalid Tristate value"),
		};
	}

	public function resultWithOverride(?Node $node, Tristate $result) : TristateResult {
		if($result->equals(Tristate::UNDEFINED())) {
			return clone TristateResult::UNDEFINED();
		}
		return new TristateResult($result, $node, $this->processorClass);
	}

}