<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\metastacking;

use jasonwynn10\LuckPerms\api\metastacking\MetaStackElement;
use jasonwynn10\LuckPerms\api\node\ChatMetaType;
use jasonwynn10\LuckPerms\api\node\types\ChatMetaNode;

class StackElementUtility implements MetaStackElement{

	/** @var callable */
	private $function;

	public function __construct(callable $function){
		$this->function = $function;
	}

	public function __invoke(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) : bool{
		return ($this->function)($type, $node, $current);
	}

	public function shouldAccumulate(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) : bool{
		throw new \BadMethodCallException("This method should never be called in utility classes");
	}
}
