<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\context;

use jasonwynn10\LuckPerms\api\context\Context;
use jasonwynn10\LuckPerms\api\context\ContextSatisfyMode;
use jasonwynn10\LuckPerms\util\Optional;
use Ramsey\Collection\Map\MapInterface;
use Ramsey\Collection\Set;

/**
 * Abstract class ContextSet.
 *
 * Represents a set of contextual key-value pairs.
 */
abstract class ContextSet implements \IteratorAggregate{

	/**
	 * Gets if this {@link ContextSet} is immutable.
	 *
	 * <p>The state of immutable instances will never change.</p>
	 *
	 * @return bool true if the set is immutable
	 */
	public abstract function isImmutable() : bool;

	/**
	 * Returns an immutable representation of this {@link ContextSet}.
	 *
	 * <p>If the set is already immutable, the same object will be returned.
	 * If the set is mutable, an immutable copy will be made.</p>
	 *
	 * @return ImmutableContextSet an immutable representation of this set
	 */
	public abstract function immutableCopy() : ImmutableContextSet;

	/**
	 * Creates a mutable copy of this {@link ContextSet}.
	 *
	 * <p>A new copy is returned regardless of the
	 * {@link #isImmutable() mutability} of this set.</p>
	 *
	 * @return MutableContextSet a mutable ContextSet
	 */
	public abstract function mutableCopy() : MutableContextSet;

	/**
	 * Returns a {@link Set} of {@link Context}s representing the current
	 * state of this {@link ContextSet}.
	 *
	 * <p>The returned set is immutable, and is a copy of the current set.
	 * (will not update live)</p>
	 *
	 * @return Set<Context> an immutable set
	 */
	public abstract function toSet() : Set;

	/**
	 * Returns a {@link MapInterface} representing the current state of this
	 * {@link ContextSet}.
	 *
	 * <p>The returned set is immutable, and is a copy of the current set.
	 * (will not update live)</p>
	 *
	 * @return MapInterface<string, Set<string>> a map
	 */
	public abstract function toMap() : MapInterface;

	/**
	 * Returns a {@link MapInterface} <b>loosely</b> representing the current state of
	 * this {@link ContextSet}.
	 *
	 * <p>The returned map is immutable, and is a copy of the current set.
	 * (will not update live)</p>
	 *
	 * <p>As a single context key can be mapped to multiple values, this method
	 * may not be a true representation of the set.</p>
	 *
	 * @return MapInterface<string, string> an immutable map
	 * @deprecated Deprecated because the returned map may not contain all data in the ContextSet
	 */
	public abstract function toFlattenedMap() : MapInterface;

	/**
	 * Returns an {@link Iterator} over each of the context pairs in this set.
	 *
	 * <p>The returned iterator represents the state of the set at the time of creation. It is not
	 * updated as the set changes.</p>
	 *
	 * <p>The iterator does not support {@link Iterator::remove()} calls.</p>
	 *
	 * @return \Iterator<Context> an iterator
	 */
	public abstract function getIterator() : \Iterator;

	/**
	 * Returns if the {@link ContextSet} contains at least one value for the
	 * given key.
	 *
	 * @param string $key the key to check for
	 *
	 * @return bool true if the set contains a value for the key
	 */
	public abstract function containsKey(string $key) : bool;

	/**
	 * Returns a Set of the values mapped to the given key.
	 *
	 * The returned set is immutable, and only represents the current state
	 * of the ContextSet. (will not update live)
	 *
	 * @param string $key the key to get values for
	 *
	 * @return Set<string> a set of values
	 * @throws NullPointerException if the key is null
	 */
	abstract public function getValues(string $key) : Set;

	/**
	 * Returns any value from this ContextSet matching the key, if present.
	 *
	 * Note that context keys can be mapped to multiple values.
	 * Use getValues(string $key) to retrieve all associated values.
	 *
	 * @param string $key the key to find values for
	 *
	 * @return Optional<string> an optional containing any match
	 */
	public function getAnyValue(string $key) : Optional{
		return Optional::ofNullable($this->getValues($key)->stream()->findAny());
	}

	/**
	 * Returns if the ContextSet contains a given context pairing.
	 *
	 * @param string $key the key to look for
	 * @param string $value the value to look for
	 *
	 * @return bool true if the set contains the context pair
	 * @throws NullPointerException if the key or value is null
	 */
	abstract public function contains(string $key, string $value) : bool;

	/**
	 * Returns if the ContextSet contains a given context pairing.
	 *
	 * @param Context $entry the entry to look for
	 *
	 * @return bool true if the set contains the context pair
	 * @throws NullPointerException if the key or value is null
	 */
	public function containsContext(Context $entry) : bool{
		return $this->contains($entry->getKey(), $entry->getValue());
	}

	/**
	 * Returns if the ContextSet contains any of the given context pairings.
	 *
	 * @param string           $key the key to look for
	 * @param iterable<string> $values the values to look for
	 *
	 * @return bool true if the set contains any of the pairs
	 * @since 5.2
	 */
	public function containsAny(string $key, iterable $values) : bool{
		foreach($values as $value){
			if($this->contains($key, $value)){
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns if this ContextSet is "satisfied" by another set.
	 *
	 * ContextSatisfyMode::AT_LEAST_ONE_VALUE_PER_KEY is the mode used by this method.
	 *
	 * @param ContextSet $other the other set
	 *
	 * @return bool true if this context set is satisfied by the other
	 */
	public function isSatisfiedBy(ContextSet $other) : bool{
		return $this->isSatisfiedByMode($other, ContextSatisfyMode::AT_LEAST_ONE_VALUE_PER_KEY());
	}

	/**
	 * Returns if this ContextSet is "satisfied" by another set, according to the given mode.
	 *
	 * @param ContextSet         $other the other set
	 * @param ContextSatisfyMode $mode the mode to use
	 *
	 * @return bool true if this context set is satisfied by the other
	 * @since 5.2
	 */
	abstract public function isSatisfiedByMode(ContextSet $other, ContextSatisfyMode $mode) : bool;

	/**
	 * Returns if the ContextSet is empty.
	 *
	 * @return bool true if the set is empty
	 */
	abstract public function isEmpty() : bool;

	/**
	 * Gets the number of context pairs in the ContextSet.
	 *
	 * @return int the size of the set
	 */
	abstract public function size() : int;
}

