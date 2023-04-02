<?php


declare(strict_types=1);

namespace jasonwynn10\LuckPerms\context;

use jasonwynn10\LuckPerms\api\context\Context;
use Ramsey\Collection\Map\AssociativeArrayMap;
use Ramsey\Collection\Set;
use function ImmutableMapBuilder;

final class ImmutableContextSetImpl extends AbstractContextSet implements ImmutableContextSet{

	public static function EMPTY() : ImmutableContextSet{
		return new self([]);
	}

	/** @var Context[] $array */
	private array $array;
	private int $size;
	private int $hashCode;

	/** @var ImmutableSetMultimap<String, String> $cachedMap */
	private ImmutableSetMultimap $cachedMap;

	public function __construct(array $contexts = []){
		$this->array = $contexts; // always sorted
		$this->size = \count($this->array);
		//$this->hashCode = Arrays::hashCode($this->array);
	}

	public function isImmutable() : bool{
		return true;
	}

	public function immutableCopy() : ImmutableContextSetImpl{
		return $this;
	}

	/**
	 * @return ImmutableSetMultimap<String, String>
	 */
	public function toMultimap() : ImmutableSetMultimap{
		if($this->cachedMap === null){
			/** @var ImmutableSetMultimapBuilder<String, String> $builder */
			$builder = new ImmutableSetMultimapBuilder();
			/** @var Context $entry */
			foreach($this->array as $entry){
				$builder->put($entry->getKey(), $entry->getValue());
			}
			$this->cachedMap = $builder->build();
		}
		return $this->cachedMap;
	}

	public function mutableCopy() : MutableContextSet{
		return new MutableContextSetImpl($this->toMultimap());
	}

	/**
	 * @return Set<Context>
	 */
	public function toSet() : Set{
		return ImmutableSet::copyOf($this->array);
	}

	/**
	 * @return AssociativeArrayMap<String, Set<String>>
	 */
	public function toMap() : AssociativeArrayMap{
		return Multimaps::asMap($this->toMultimap());
	}

	/**
	 * @return AssociativeArrayMap<String, String>
	 */
	public function toFlattenedMap() : AssociativeArrayMap{
		/** @var ImmutableMapBuilder<String, String> $m */
		$m = \ImmutableMapBuilder();
		/** @var Context $e */
		foreach($this->array as $e){
			$m->put($e->getKey(), $e->getValue());
		}
		return $m->build();
	}

	/**
	 * @return Context[]
	 */
	public function toArray() : array{
		return $this->array; // only used read-only & internally
	}
}
