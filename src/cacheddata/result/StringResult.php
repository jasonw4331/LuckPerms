<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\cacheddata\result;

use jasonwynn10\LuckPerms\api\node\Node;
use jasonwynn10\LuckPerms\api\node\types\ChatMetaNode;
use jasonwynn10\LuckPerms\api\node\types\MetaNode;

/**
 * @template N
 * @phpstan-extends Result<string, N>
 */
class StringResult implements Result{

	/**
	 * @phpstan-param string|null       $result
	 * @phpstan-param N|null         $node
	 * @phpstan-param StringResult<N>|null $overriddenResult
	 */
	public function __construct(private ?string $result, private ?Node $node, private ?StringResult $overriddenResult){}

	/**
	 * @inheritDoc
	 */
	public function result() : ?string{
		return $this->result;
	}

	/**
	 * @inheritDoc
	 */
	public function node() : ?Node{
		return $this->node;
	}

	/**
	 * @phpstan-return StringResult<N>|null
	 */
	public function overriddenResult() : StringResult{
		return $this->overriddenResult;
	}

	/**
	 * @phpstan-param StringResult<N> $overriddenResult
	 */
	public function setOverriddenResult(StringResult $overriddenResult) : void{
		$this->overriddenResult = $overriddenResult;
	}

	/**
	 * @phpstan-return StringResult<N>
	 */
	public function copy() : StringResult{
		return new StringResult($this->result, $this->node, $this->overriddenResult);
	}

	public function __toString() : string{
		return "StringResult(" .
			"result=" . $this->result . ", " .
			"node=" . $this->node . ", " .
			"overriddenResult=" . $this->overriddenResult . ')';
	}

	/**
	 * @phpstan-return StringResult<null>
	 */
	public static function nullResult() : StringResult {
		return new StringResult(null, null, null);
	}

	/**
	 * @phpstan-template Ntype of Node
	 * @phpstan-param Ntype $node
	 * @phpstan-return StringResult<Ntype>
	 */
	public static function of(string $result, ?Node $node = null) : StringResult {
		return new StringResult($result, $node, null);
	}

	/**
	 * @phpstan-return StringResult<MetaNode>
	 */
	public static function ofMetaNode(MetaNode $node) : StringResult {
		return new StringResult($node->getMetaValue(), $node, null);
	}

	/**
	 * @phpstan-template Ntype of ChatMetaNode
	 * @phpstan-param Ntype $node
	 * @phpstan-return StringResult<Ntype>
	 */
	public static function ofNode(Node $node) : StringResult {
		return new StringResult($node->getMetaValue(), $node, null);
	}

}