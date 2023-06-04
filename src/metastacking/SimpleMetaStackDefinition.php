<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\metastacking;

use jasonwynn10\LuckPerms\api\metastacking\DuplicateRemovalFunction;
use jasonwynn10\LuckPerms\api\metastacking\MetaStackDefinition;

class SimpleMetaStackDefinition implements MetaStackDefinition{

	private array $elements = [];
	private DuplicateRemovalFunction $duplicateRemovalFunction;
	private string $startSpacer;
	private string $middleSpacer;
	private string $endSpacer;

	// cache hashcode - this class is immutable, and used an index in MetaContexts
	private int $hashCode;
	private $parseList;

	public function __construct(array $elements, DuplicateRemovalFunction $duplicateRemovalFunction, string $startSpacer, string $middleSpacer, string $endSpacer){
		$this->elements = $elements;
		$this->duplicateRemovalFunction = $duplicateRemovalFunction;
		$this->startSpacer = $startSpacer;
		$this->middleSpacer = $middleSpacer;
		$this->endSpacer = $endSpacer;
		$this->hashCode = $this->calculateHashCode();
	}

	public function getElements() : array{
		// TODO: Implement getElements() method.
	}

	public function getDuplicateRemovalFunction() : DuplicateRemovalFunction{
		// TODO: Implement getDuplicateRemovalFunction() method.
	}

	public function getStartSpacer() : string{
		// TODO: Implement getStartSpacer() method.
	}

	public function getMiddleSpacer() : string{
		// TODO: Implement getMiddleSpacer() method.
	}

	public function getEndSpacer() : string{
		// TODO: Implement getEndSpacer() method.
	}

	private function calculateHashCode() : int{
		return 0; // TODO: hash multiple object together
	}
}
