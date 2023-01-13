<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\query;

use jasonwynn10\LuckPerms\api\query\QueryOptions;
use jasonwynn10\LuckPerms\context\ImmutableContextSet;
use jasonwynn10\LuckPerms\context\ImmutableContextSetImpl;
use Ramsey\Collection\Set;

class QueryOptionsBuilderImpl implements Builder{
	private QueryMode $mode;
	private ImmutableContextSet $context;
	private int $flags;
	private ?Set $flagsSet;
	private ?array $options;
	private bool $copyOptions;

	public function __construct(QueryMode $mode){
		$this->mode = $mode;
		$this->context = $mode === QueryMode::CONTEXTUAL() ? ImmutableContextSetImpl::EMPTY() : null;
		$this->flags = FlagUtils::ALL_FLAGS();
		$this->flagsSet = null;
		$this->options = null;
		$this->copyOptions = false;
	}

	public function mode(QueryMode $mode) : Builder {
		if($this->mode === $mode) {
			return $this;
		}

		$this->mode = $mode;
		$this->context = $this->mode === QueryMode::CONTEXTUAL() ? ImmutableContextSetImpl::EMPTY() : null;
		return $this;
	}

	public function flag(Flag $flag, bool $value) : Builder {
		if($this->flagsSet === null and FlagUtils::read($this->flags, $flag) === $value) {
			return $this;
		}

		if($this->flagsSet === null) {
			$this->flagsSet = FlagUtils::toSet($this->flags);
		}
		if($value){
			$this->flagsSet->add($flag);
		}else{
			$this->flagsSet->remove($flag);
		}

		return $this;
	}

	/**
	 * @param Set<Flag> $flags
	 *
	 * @return Builder
	 */
	public function flags(Set $flags) : Builder{
		foreach($flags as $flag)
			\assert($flag instanceof Flag);
		$this->flagsSet = $flags;
		return $this;
	}

	public function option($key, $value) : Builder{
		if($this->options === null or $this->copyOptions){
			if($this->options === null){
				$this->options = [];
			}
			$this->copyOptions = false;
		}
		if($value === null) {
			unset($this->options[$key]);
		}else{
			$this->options[$key] = $value;
		}

		if(\count($this->options) < 1){
			$this->options = null;
		}

		return $this;
	}

	public function build() : QueryOptions {
		$flags = $this->flagsSet !== null ? FlagUtils::toByte($this->flagsSet) : $this->flags;

		if($this->options === null) {
			if($this->mode === QueryMode::NON_CONTEXTUAL()) {
				if(FlagUtils::ALL_FLAGS() === $flags) {
					return QueryOptionsImpl::DEFAULT_NON_CONTEXTUAL();
				}
			}elseif($this->mode === QueryMode::CONTEXTUAL()) {
				if(FlagUtils::ALL_FLAGS() === $flags and empty($this->context)) {
					return QueryOptionsImpl::DEFAULT_CONTEXTUAL();
				}
			}
		}

		return new QueryOptionsImpl($this->mode, $this->context, $flags, $this->options);
	}
}