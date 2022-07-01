<?php

declare(strict_types=1);

namespace Marvin255\Optional;

use InvalidArgumentException;
use Throwable;

/**
 * A container object which may or may not contain a non-null value.
 * If a value is present, isPresent() will return true and get() will return the value.
 *
 * @psalm-template TNested
 */
final class Optional
{
    /**
     * @psalm-var TNested
     */
    private readonly mixed $data;

    private readonly bool $isPresent;

    /**
     * @psalm-param TNested $data
     * @psalm-param bool $isPresent
     */
    private function __construct(mixed $data, bool $isPresent)
    {
        $this->data = $data;
        $this->isPresent = $isPresent;
    }

    /**
     * Returns an Optional with the specified present non-null value.
     *
     * @throws InvalidArgumentException
     *
     * @psalm-template T
     * @psalm-param T $data
     * @psalm-return (
     *     T is string ? self<string> : (
     *         T is int ? self<int> : (
     *             T is bool ? self<bool> : (
     *                 T is float ? self<float> : self<T>
     *             )
     *         )
     *     )
     * )
     */
    public static function of(mixed $data): self
    {
        if ($data === null) {
            throw new InvalidArgumentException("Value can't be null");
        }

        return new self($data, true);
    }

    /**
     * Returns an empty Optional instance.
     */
    public static function empty(): self
    {
        return new self(null, false);
    }

    /**
     * Returns an Optional describing the specified value, if non-null, otherwise returns an empty Optional.
     *
     * @psalm-template T
     * @psalm-param T $data
     * @psalm-return (
     *     T is string ? self<string> : (
     *         T is int ? self<int> : (
     *             T is bool ? self<bool> : (
     *                 T is float ? self<float> : self<T>
     *             )
     *         )
     *     )
     * )
     */
    public static function ofNullable(mixed $data): self
    {
        return $data === null ? self::empty() : self::of($data);
    }

    /**
     * If a value is present, and the value matches the given predicate,
     * return an Optional describing the value, otherwise return an empty Optional.
     *
     * @psalm-param callable(TNested): bool $filter
     * @psalm-return Optional<TNested>
     */
    public function filter(callable $filter): self
    {
        if ($this->isPresent && \call_user_func($filter, $this->data)) {
            return $this;
        }

        return self::empty();
    }

    /**
     * If a value is present in this Optional, returns the value, otherwise throws NoSuchElementException.
     *
     * @throws NoSuchElementException
     *
     * @psalm-return TNested
     */
    public function get(): mixed
    {
        if (!$this->isPresent) {
            throw new NoSuchElementException('There is no data set for this optional');
        }

        return $this->data;
    }

    /**
     * Return true if there is a value present, otherwise false.
     */
    public function isPresent(): bool
    {
        return $this->isPresent;
    }

    /**
     * If a value is present, invoke the specified consumer with the value, otherwise do nothing.
     *
     * @psalm-param callable(TNested): void $consumer
     */
    public function ifPresent(callable $consumer): void
    {
        if ($this->isPresent) {
            \call_user_func($consumer, $this->data);
        }
    }

    /**
     * Return the value if present, otherwise return other.
     *
     * @psalm-param TNested $other
     * @psalm-return TNested
     */
    public function orElse(mixed $other): mixed
    {
        return $this->isPresent ? $this->data : $other;
    }

    /**
     * Return the value if present, otherwise invoke other and return the result of that invocation.
     *
     * @psalm-param callable(): TNested $other
     * @psalm-return TNested
     */
    public function orElseGet(callable $other): mixed
    {
        return $this->isPresent ? $this->data : \call_user_func($other);
    }

    /**
     * Return the contained value, if present, otherwise throw an exception to be created by the provided supplier.
     *
     * @throws Throwable
     *
     * @psalm-param callable(): Throwable $exceptionSupplier
     * @psalm-return TNested
     */
    public function orElseThrow(callable $exceptionSupplier): mixed
    {
        if ($this->isPresent) {
            return $this->data;
        }

        throw \call_user_func($exceptionSupplier);
    }
}
