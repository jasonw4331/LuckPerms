<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\cacheddata\type;

interface DoubleSelectionPredicate{
	public function shouldSelect(float $value, float $current) : bool;
}
