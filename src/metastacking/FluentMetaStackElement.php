<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\metastacking;

use jasonwynn10\LuckPerms\api\metastacking\MetaStackElement;
use jasonwynn10\LuckPerms\api\node\ChatMetaType;
use jasonwynn10\LuckPerms\api\node\types\ChatMetaNode;
use Ramsey\Collection\Map\AbstractTypedMap;

final class FluentMetaStackElement implements MetaStackElement{

	public static function builder(string $name) : FluentMetaStackElementBuilder{
		return new FluentMetaStackElementBuilder($name);
	}

	private string $toString;

	/**
	 * @param AbstractTypedMap<string, string> $params
	 * @param List<MetaStackElement>           $subElements
	 */
	public function __construct(string $name, AbstractTypedMap $params, private array $subElements){
		$this->toString = $this->formToString($name, $params);
	}

	public function shouldAccumulate(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) : bool{
		foreach($this->subElements as $element){
			if(!$element->shouldAccumulate($type, $node, $current)){
				return false;
			}
		}
		return true;
	}

	public function __toString() : string{
		return $this->toString;
	}

	/**
	 * @param AbstractTypedMap<string, string> $params
	 */
	private static function formToString(string $name, AbstractTypedMap $params) : string{
		return $name . "(" . \implode(', ', \array_map(static fn($p) => $p->getKey() . '=' . $p->getValue(), $params->toArray())) . ")";
	}
}
