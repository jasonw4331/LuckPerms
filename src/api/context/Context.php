<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\context;

use function strlen;

/**
 * Represents an individual context pair.
 *
 * Context keys and values may not be null or empty. A key/value will be
 * deemed empty if its length is zero, or if it consists of only space
 * characters.
 *
 * @see ContextSet
 */
abstract class Context{

	/**
	 * Tests whether $key is valid.
	 *
	 * Context keys and values may not be null or empty. A key/value will be
	 * deemed empty if its length is zero, or if it consists of only space
	 * characters.
	 *
	 * An exception is thrown when an invalid key is added to a ContextSet.
	 *
	 * @param string|null $key the key to test
	 *
	 * @return bool true if valid, false otherwise.
	 * @since 5.1
	 */
	public static function isValidKey(?string $key) : bool{
		if($key === null || $key === ''){
			return false;
		}

		// look for a non-whitespace character
		for($i = 0, $n = strlen($key); $i < $n; $i++){
			if($key[$i] !== ' '){
				return true;
			}
		}

		return false;
	}

	/**
	 * Tests whether $value is valid.
	 *
	 * Context keys and values may not be null or empty. A key/value will be
	 * deemed empty if its length is zero, or if it consists of only space
	 * characters.
	 *
	 * An exception is thrown when an invalid value is added to a ContextSet.
	 *
	 * @param string|null $value the value to test
	 *
	 * @return bool true if valid, false otherwise.
	 * @since 5.1
	 */
	public static function isValidValue(?string $value) : bool{
		return self::isValidKey($value); // the same for now...
	}

	/**
	 * Gets the context key.
	 *
	 * @return string the key
	 */
	abstract public function getKey() : string;

	/**
	 * Gets the context value.
	 *
	 * @return string the value
	 */
	abstract public function getValue() : string;

}
