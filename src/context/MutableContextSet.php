<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\context;

use jasonw4331\LuckPerms\api\context\Context;
use jasonw4331\LuckPerms\api\LuckPermsProvider;

abstract class MutableContextSet extends ContextSet{

	/**
	 * Creates a new empty MutableContextSet.
	 *
	 * @return MutableContextSet a new MutableContextSet
	 */
	public static function create() : MutableContextSet{
		return LuckPermsProvider::get()->getContextManager()->getContextSetFactory()->mutable();
	}

	/**
	 * Creates a MutableContextSet from a context pair.
	 *
	 * @param string $key   the key
	 * @param string $value the value
	 *
	 * @return MutableContextSet a new MutableContextSet containing one context pair
	 */
	public static function of(string $key, string $value) : MutableContextSet{
		$set = static::create();
		$set->add($key, $value);
		return $set;
	}

	/**
	 * Adds a context to this set.
	 *
	 * @param string $key   the key to add
	 * @param string $value the value to add
	 */
	abstract public function add(string $key, string $value) : void;

	/**
	 * Adds a context to this set.
	 *
	 * @param Context $entry the entry to add
	 *
	 * @throws \InvalidArgumentException if the $entry is null
	 */
	public function addContext(Context $entry) : void{
		$this->add($entry->getKey(), $entry->getValue());
	}

	/**
	 * Adds the contexts contained in the given iterable to this set.
	 *
	 * @param iterable<Context> $iterable an iterable of key value context pairs
	 */
	public function addAll(iterable $iterable) : void{
		foreach($iterable as $e){
			$this->add($e);
		}
	}

	/**
	 * Adds all the contexts in another ContextSet to this set.
	 *
	 * @param ContextSet $contextSet the set to add from
	 */
	abstract public function addAllContexts(ContextSet $contextSet) : void;

	/**
	 * Removes a context from this set.
	 *
	 * @param string $key   the key to remove
	 * @param string $value the value to remove
	 */
	abstract public function remove(string $key, string $value) : void;

	/**
	 * Removes all contexts from this set with the given key.
	 *
	 * @param string $key the key to remove
	 */
	abstract public function removeAll(string $key) : void;

	/**
	 * Removes all contexts from the set.
	 */
	abstract public function clear() : void;
}
