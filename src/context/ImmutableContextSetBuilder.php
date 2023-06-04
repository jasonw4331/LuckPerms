<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\context;

use jasonwynn10\LuckPerms\api\context\Context;

/**
 * A builder for {@link ImmutableContextSet}.
 */
abstract class ImmutableContextSetBuilder{

	/**
	 * Adds a context to the set.
	 *
	 * @param string $key   the key to add
	 * @param string $value the value to add
	 *
	 * @return ImmutableContextSetBuilder the builder
	 * @see MutableContextSet#add(string, string)
	 */
	abstract public function add(string $key, string $value) : ImmutableContextSetBuilder;

	/**
	 * Adds a context to the set.
	 *
	 * @param Context $entry the entry to add
	 *
	 * @return ImmutableContextSetBuilder the builder
	 * @see MutableContextSet#add(Context)
	 */
	public function addContext(Context $entry) : ImmutableContextSetBuilder{
		$this->add($entry->getKey(), $entry->getValue());
		return $this;
	}

	/**
	 * Adds the contexts contained in the given iterable to the set.
	 *
	 * @param iterable $iterable an iterable of key value context pairs
	 *
	 * @return ImmutableContextSetBuilder the builder
	 * @see MutableContextSet#addAll(Iterable)
	 */
	public function addAll(iterable $iterable) : ImmutableContextSetBuilder{
		foreach($iterable as $e){
			$this->addContext($e);
		}
		return $this;
	}

	/**
	 * Adds all the contexts in another ContextSet to the set.
	 *
	 * @param ContextSet $contextSet the set to add from
	 *
	 * @return ImmutableContextSetBuilder the builder
	 * @see MutableContextSet#addAll(ContextSet)
	 */
	abstract public function addAllContexts(ContextSet $contextSet) : ImmutableContextSetBuilder;

	/**
	 * Creates an ImmutableContextSet from the values previously added to the builder.
	 *
	 * @return ImmutableContextSet an ImmutableContextSet from the builder
	 */
	abstract public function build() : ImmutableContextSet;

}
