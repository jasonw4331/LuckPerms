<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\context;

use jasonwynn10\LuckPerms\api\LuckPermsProvider;

abstract class ImmutableContextSet extends ContextSet{

	/**
	 * Creates an {@link ImmutableContextSet.Builder}.
	 *
	 * @return ImmutableContextSetBuilder a new ImmutableContextSet builder
	 */
	static public function builder() : ImmutableContextSetBuilder{
		return LuckPermsProvider::get()->getContextManager()->getContextSetFactory()->immutableBuilder();
	}

	/**
	 * Returns an empty {@link ImmutableContextSet}.
	 *
	 * @return ImmutableContextSet an empty ImmutableContextSet
	 */
	static public function empty() : ImmutableContextSet{
		return LuckPermsProvider::get()->getContextManager()->getContextSetFactory()->immutableEmpty();
	}

	/**
	 * Creates an {@link ImmutableContextSet} from a context pair.
	 *
	 * @param string $key   the key
	 * @param string $value the value
	 *
	 * @return ImmutableContextSet a new ImmutableContextSet containing one context pair
	 */
	static public function of(string $key, string $value) : ImmutableContextSet{
		return LuckPermsProvider::get()->getContextManager()->getContextSetFactory()->immutableOf($key, $value);
	}

	/**
	 * @deprecated This context set is already immutable!
	 */
	abstract public function immutableCopy() : ImmutableContextSet;

}
