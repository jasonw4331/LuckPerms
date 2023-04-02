<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\api\query\meta;

interface MetaValueSelector{

	public function selectValue(string $key, array $values) : string;

}
