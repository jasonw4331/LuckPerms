<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\metastacking;

interface MetaStackDefinition{
	const PREFIX_STACK_KEY = 'prefixstack';
	const SUFFIX_STACK_KEY = 'suffixstack';

	public function getElements() : array;

	public function getDuplicateRemovalFunction() : DuplicateRemovalFunction;

	public function getStartSpacer() : string;

	public function getMiddleSpacer() : string;

	public function getEndSpacer() : string;

}
