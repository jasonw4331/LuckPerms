<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\api\metastacking;

interface MetaStackDefinition{
	CONST PREFIX_STACK_KEY = 'prefixstack';
	CONST SUFFIX_STACK_KEY = 'suffixstack';

	public function getElements() : array;
	public function getDuplicateRemovalFunction() : DuplicateRemovalFunction;
	public function getStartSpacer() : string;
	public function getMiddleSpacer() : string;
	public function getEndSpacer() : string;

}