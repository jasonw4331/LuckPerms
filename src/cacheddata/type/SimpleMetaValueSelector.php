<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\cacheddata\type;

use jasonwynn10\LuckPerms\api\query\meta\MetaValueSelector;

class SimpleMetaValueSelector implements MetaValueSelector{
	/** @var Strategy[] */
	private array $strategies;
	private Strategy $defaultStrategy;

	/**
	 * @param Strategy[] $strategies
	 */
	public function __construct(array $strategies, Strategy $defaultStrategy){
		$this->strategies = $strategies;
		$this->defaultStrategy = $defaultStrategy;
	}

	public function selectValue(string $key, array $values) : string{
		switch(\count($values)){
			case 0:
				throw new \InvalidArgumentException('values is empty');
			case 1:
				return $values[0];
			default:
				return ($this->strategies[$key] ?? $this->defaultStrategy)->select($values);
		}
	}

	public static function selectNumber(array $values, DoubleSelectionPredicate $selection) : string{
		$current = 0;
		$selected = null;

		foreach($values as $value){
			if(\ctype_digit($value)){
				$parse = (float) $value;
				if($selected === null || $selection->shouldSelect($parse, $current)){
					$selected = $value;
					$current = $parse;
				}
			}
		}

		return $selected != null ? $selected : Strategy::INHERITANCE()->select($values);
	}
}
