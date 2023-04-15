<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\util;

use pocketmine\utils\Utils;

/**
 * A final container class representing an optional value that may or may not be present.
 * If a value is present, {@code isPresent()} returns {@code true}. If no value is present,
 * the object is considered <i>empty</i> and {@code isPresent()} returns {@code false}.
 * <p>Additional methods are provided that depend on the presence or absence of a contained
 * value, such as {@link #orElse(Object) orElse()} (returns a default value if no value is present)
 * and {@link #ifPresent(Consumer) ifPresent()} (performs an action if a value is present).
 * <p>This is a <a href="../lang/doc-files/ValueBased.html">value-based</a> class,
 * and identity-sensitive operations (including reference equality ({@code ==}),
 * identity hash code, or synchronization) on instances of {@code Optional} may have
 * unpredictable results and should be avoided.
 * @apiNote
 * {@code Optional} is primarily intended for use as a method return type where there
 * is a clear need to represent "no result," and where using {@code null} is likely to
 * cause errors. A variable whose type is {@code Optional} should never be {@code null};
 * it should always point to an {@code Optional} instance.
 * @phpstan-template Tvalue the type of the value
 * @since 1.8
 */
final class Optional{

	/**
	 * @param Tvalue $value
	 */
	private function __construct(private mixed $value = null){
	}

	/**
	 * Returns an empty Optional instance.  No value is present for this
	 * Optional.
	 *
	 * @apiNote
	 * Though it may be tempting to do so, avoid testing if an object is empty
	 * by comparing with == against instances returned by
	 * Optional::empty().  There is no guarantee that it is a singleton.
	 * Instead, use isPresent().
	 *
	 * @return Optional<null> an empty Optional
	 */
	public static function empty() : Optional{
		return clone new Optional();
	}

	/**
	 * Returns an Optional describing the given value.
	 *
	 * @phpstan-template T
	 *
	 * @param T $value The value to describe, which must be non-null
	 *
	 * @return Optional<T> An Optional with a present value if the specified value
	 * is non-null, otherwise an empty Optional
	 */
	public static function of(mixed $value) : Optional{
		return new Optional($value);
	}

	/**
	 * Returns an Optional describing the given value.
	 *
	 * @phpstan-template T
	 *
	 * @param T $value The value to describe, which must be non-null
	 *
	 * @return Optional<T> An Optional with a present value if the specified value
	 * is non-null, otherwise an empty Optional
	 */
	public static function ofNullable(mixed $value) : Optional{
		return $value === null ? self::empty() : self::of($value);
	}

	/**
	 * If a value is present, returns the value, otherwise throws an
	 * exception.
	 *
	 * @return Tvalue The value described by this Optional
	 */
	public function get() : mixed{
		return $this->value;
	}

	/**
	 * If a value is present, returns true, otherwise false.
	 *
	 * @return bool True if a value is present, otherwise false
	 */
	public function isPresent() : bool{
		return $this->value !== null;
	}

	/**
	 * If a value is not present, returns true, otherwise false.
	 *
	 * @return bool True if a value is not present, otherwise false
	 */
	public function isEmpty() : bool{
		return $this->value === null;
	}

	/**
	 * If a value is present, performs the given action with the value,
	 * otherwise does nothing.
	 *
	 * @param \Closure(Tvalue) : void $action The action to be performed, if a value is present
	 */
	public function ifPresent(callable $action) : void{
		Utils::validateCallableSignature(function(mixed $value) : void{ }, $action);
		if($this->value !== null){
			$action($this->value);
		}
	}

	/**
	 * If a value is present, performs the given action with the value,
	 * otherwise performs the given empty-based action.
	 *
	 * @param \Closure(Tvalue) : void $action The action to be performed, if a value is present
	 * @param \Closure() : void       $emptyAction The empty-based action to be performed, if no value is present
	 */
	public function ifPresentOrElse(callable $action, callable $emptyAction) : void{
		Utils::validateCallableSignature(function(mixed $value) : void{ }, $action);
		Utils::validateCallableSignature(function() : void{ }, $emptyAction);
		if($this->value !== null){
			$action($this->value);
		}else{
			$emptyAction();
		}
	}

	/**
	 * If a value is present, and the value matches the given predicate,
	 * returns an `Optional` describing the value, otherwise returns an
	 * empty `Optional`.
	 *
	 * @param \Closure(Tvalue) : bool $predicate the predicate to apply to a value, if present
	 *
	 * @return Optional<Tvalue>|Optional<null> describing the value of this Optional, if a value is present and the value matches the
	 *         given predicate, otherwise an empty Optional
	 */
	public function filter(callable $predicate) : Optional{
		Utils::validateCallableSignature(function(mixed $value) : bool{ return true; }, $predicate);
		if($this->value === null){
			return $this;
		}
		return $predicate($this->value) ? $this : self::empty();
	}

	/**
	 * If a value is present, returns an Optional describing (as if by
	 * `ofNullable`) the result of applying the given mapping function to
	 * the value, otherwise returns an empty Optional.
	 *
	 * If the mapping function returns a `null` result then this method
	 * returns an empty Optional.
	 *
	 * @apiNote
	 * This method supports post-processing on Optional values, without
	 * the need to explicitly check for a return status.  For example, the
	 * following code traverses a stream of URIs, selects one that has not
	 * yet been processed, and creates a path from that URI, returning
	 * an `Optional<Path>`:
	 *
	 * Optional<Path> p =
	 *     uris.stream().filter(uri -> !isProcessedYet(uri))
	 *                   .findFirst()
	 *                   .map(Paths::get);
	 *
	 * Here, `findFirst` returns an `Optional<URI>`, and then
	 * `map` returns an `Optional<Path>` for the desired
	 * URI if one exists.
	 *
	 * @phpstan-template T
	 * @phpstan-param \Closure(Tvalue) : T $mapper the mapping function to apply to a value, if present
	 * @return Optional<T>|Optional<null> describing the result of applying a mapping function to the value of this Optional, if a value is
	 *         present, otherwise an empty Optional
	 */
	public function map(callable $mapper) : Optional{
		Utils::validateCallableSignature(function(mixed $value) : mixed{ return $value; }, $mapper);
		if($this->value === null){
			return self::empty();
		}
		return self::ofNullable($mapper($this->value));
	}

	/**
	 * If a value is present, returns the result of applying the given
	 * {@code Optional}-bearing mapping function to the value, otherwise returns
	 * an empty {@code Optional}.
	 *
	 * <p>This method is similar to {@link #map(Function)}, but the mapping
	 * function is one whose result is already an {@code Optional}, and if
	 * invoked, {@code flatMap} does not wrap it within an additional
	 * {@code Optional}.
	 *
	 * @phpstan-template T
	 * @phpstan-param \Closure(Tvalue) : Optional<T> $mapper the mapping function to apply to a value, if present
	 * @return Optional<T>|Optional<null> the result of applying an {@code Optional}-bearing mapping
	 *         function to the value of this {@code Optional}, if a value is
	 *         present, otherwise an empty {@code Optional}
	 */
	public function flatMap(callable $mapper) : Optional{
		Utils::validateCallableSignature(function(mixed $value) : Optional{ return Optional::empty(); }, $mapper);
		if($this->value === null){
			return self::empty();
		}
		return $mapper($this->value);
	}

	/**
	 * If a value is present, returns an {@code Optional} describing the value,
	 * otherwise returns an {@code Optional} produced by the supplying function.
	 *
	 * @phpstan-template T
	 * @phpstan-param \Closure() : Optional<T> $supplier the supplying function that produces an {@code Optional}
	 *        to be returned
	 * @return Optional<Tvalue>|Optional<T> returns an {@code Optional} describing the value of this
	 *         {@code Optional}, if a value is present, otherwise an
	 *         {@code Optional} produced by the supplying function.
	 * @throws \InvalidArgumentException if the supplying function is null or
	 *                                  produces a null result.
	 * @since 7.3
	 */
	public function or(\Closure $supplier) : Optional{
		if($this->value !== null){
			return $this;
		}else{
			$r = $supplier();
			if($r instanceof Optional){
				return $r;
			}else{
				throw new \InvalidArgumentException("Supplied function must return an Optional.");
			}
		}
	}

	/**
	 * If a value is present, returns the value, otherwise returns
	 * $other.
	 *
	 * @phpstan-template T
	 *
	 * @param T $other the value to be returned, if no value is present.
	 *
	 * @return Tvalue|T the value, if present, otherwise $other
	 */
	public function orElse(mixed $other) : mixed{
		return $this->value !== null ? $this->value : $other;
	}

	/**
	 * If a value is present, returns the value, otherwise returns the result
	 * produced by the supplying function.
	 *
	 * @phpstan-template T
	 * @phpstan-param \Closure() : T $supplier the supplying function that produces a value to be returned
	 * @return Tvalue|T the value, if present, otherwise the result produced by the
	 *         supplying function
	 */
	public function orElseGet(callable $supplier) : mixed{
		Utils::validateCallableSignature(function() : mixed{ return null; }, $supplier);
		return $this->value !== null ? $this->value : $supplier();
	}

	/**
	 * If a value is present, returns the value, otherwise throws
	 * NoSuchElementException.
	 *
	 * @return mixed the non-null value described by this Optional
	 * @throws \Exception if no value is present
	 * @since 10
	 */
	public function orElseThrow() : mixed{
		if($this->value === null){
			throw new \Exception("No value present");
		}
		return $this->value;
	}

	/**
	 * Indicates whether some other object is "equal to" this {@code Optional}.
	 * The other object is considered equal if:
	 * <ul>
	 * <li>it is also an {@code Optional} and;
	 * <li>both instances have no value present or;
	 * <li>the present values are "equal to" each other via {@code equals()}.
	 * </ul>
	 *
	 * @param Optional $obj an object to be tested for equality
	 *
	 * @return bool {@code true} if the other object is "equal to" this object
	 *         otherwise {@code false}
	 */
	public function equals(Optional $obj) : bool{
		if($this === $obj){
			return true;
		}

		$other = $obj;
		return $this->value === $other->value;
	}

	/**
	 * Returns the hash code of the value, if present, otherwise {@code 0}
	 * (zero) if no value is present.
	 *
	 * @return int hash code value of the present value or {@code 0} if no value is
	 *         present
	 */
	public function hashCode() : int{
		return $this->value !== null ? \hash($this->value) : 0;
	}

	/**
	 * Returns a non-empty string representation of this {@code Optional}
	 * suitable for debugging. The exact presentation format is unspecified and
	 * may vary between implementations and versions.
	 *
	 * @implSpec
	 * If a value is present the result must include its string representation
	 * in the result. Empty and present {@code Optional}s must be unambiguously
	 * differentiable.
	 *
	 * @return string the string representation of this instance
	 */
	public function __toString() : string{
		return $this->value !== null
			? sprintf("Optional[%s]", $this->value)
			: "Optional.empty";
	}
}
