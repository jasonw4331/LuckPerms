<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\metastacking;

use jasonwynn10\LuckPerms\api\metastacking\MetaStackElement;
use Ramsey\Collection\Map\AbstractTypedMap;
use Ramsey\Collection\Map\TypedMap;

final class FluentMetaStackElementBuilder{
	private array $elements = [];
	private AbstractTypedMap $params;

	public function __construct(private string $name){
		$this->params = new TypedMap('string', 'string', []);
	}

	public function with(MetaStackElement $element) : self{
		$this->elements[] = $element;
		return $this;
	}

	public function param(string $name, string $value) : self{
		$this->params->put($name, $value);
		return $this;
	}

	public function build() : MetaStackElement{
		return new FluentMetaStackElement($this->name, $this->params, $this->elements);
	}

}
