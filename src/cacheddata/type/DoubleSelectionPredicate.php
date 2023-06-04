<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\cacheddata\type;

interface DoubleSelectionPredicate{
	public function shouldSelect(float $value, float $current) : bool;
}
