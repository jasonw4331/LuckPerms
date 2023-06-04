<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\cacheddata\result;

use jasonwynn10\LuckPerms\api\node\Node;
use jasonwynn10\LuckPerms\api\util\Tristate;
use jasonwynn10\LuckPerms\calculator\processor\PermissionProcessor;

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