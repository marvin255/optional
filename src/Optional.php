<?php

declare(strict_types=1);

namespace Marvin255\Optional;

use Throwable;

/**
 * A container object which may or may not contain a non-null value.
 * If a value is present, isPresent() will return true and get() will return the value.
 *
 * @template TNested
 *
 * @psalm-api
 */
final class Optional
{
    private function __construct(
        /** @var TNested $data */
        private readonly mixed $data,
        private readonly bool $isPresent
    ) {
    }

    /**
     * Returns an Optional with the specified present non-null value.
     *
     * @template T
     *
     * @param T $data
     *
     * @return self<T>
     *
     * @throws \InvalidArgumentException
     */
    public static function of(mixed $data): self
    {
        if (null === $data) {
            throw new \InvalidArgumentException("Value can't be null");
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
     * @template T
     *
     * @param T $data
     *
     * @return self<T>
     */
    public static function ofNullable(mixed $data): self
    {
        return null === $data ? self::empty() : self::of($data);
    }

    /**
     * If a value is present, and the value matches the given predicate,
     * return an Optional describing the value, otherwise return an empty Optional.
     *
     * @return self<TNested>
     *
     * @psalm-param callable(TNested): bool $filter
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
     * @return TNested
     *
     * @throws NoSuchElementException
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
     * @param TNested $other
     *
     * @return TNested
     */
    public function orElse(mixed $other): mixed
    {
        return $this->isPresent ? $this->data : $other;
    }

    /**
     * Return the value if present, otherwise invoke other and return the result of that invocation.
     *
     * @return TNested
     *
     * @psalm-param callable(): TNested $other
     */
    public function orElseGet(callable $other): mixed
    {
        return $this->isPresent ? $this->data : \call_user_func($other);
    }

    /**
     * Return the contained value, if present, otherwise throw an exception to be created by the provided supplier.
     *
     * @return TNested
     *
     * @throws \Throwable
     *
     * @psalm-param callable(): Throwable $exceptionSupplier
     */
    public function orElseThrow(callable $exceptionSupplier): mixed
    {
        if ($this->isPresent) {
            return $this->data;
        }

        throw \call_user_func($exceptionSupplier);
    }
}
