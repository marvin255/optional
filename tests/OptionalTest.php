<?php

declare(strict_types=1);

namespace Marvin255\Optional\Tests;

use Marvin255\Optional\NoSuchElementException;
use Marvin255\Optional\Optional;
use Marvin255\Optional\OptionalException;

/**
 * @internal
 */
final class OptionalTest extends BaseCase
{
    public function testOf(): void
    {
        $optional = Optional::of('test');

        $this->assertInstanceOf(Optional::class, $optional);
    }

    public function testOfWithNull(): void
    {
        $this->expectException(OptionalException::class);
        Optional::of(null);
    }

    public function testEmpty(): void
    {
        $optional = Optional::empty();

        $this->assertInstanceOf(Optional::class, $optional);
    }

    public function testOfNullable(): void
    {
        $optional = Optional::ofNullable(null);
        $res = $optional->isPresent();

        $this->assertFalse($res);
    }

    public function testOfNullableNotNull(): void
    {
        /** @var string */
        $value = 'qwe';
        $optional = Optional::ofNullable($value);
        $isPresent = $optional->isPresent();
        $res = $optional->get();

        $this->assertTrue(
            $isPresent,
            'optional with data must return true from isPresent'
        );
        $this->assertSame(
            $value,
            $res,
            'optional must return contained value'
        );
    }

    public function testGet(): void
    {
        /** @var string */
        $value = 'test';

        $optional = Optional::of($value);
        $res = $optional->get();

        $this->assertSame($value, $res);
    }

    public function testGetNoValueException(): void
    {
        $optional = Optional::empty();

        $this->expectException(NoSuchElementException::class);
        $optional->get();
    }

    public function testIsPresent(): void
    {
        $value = 'test';

        $optional = Optional::of($value);
        $res = $optional->isPresent();

        $this->assertTrue($res);
    }

    public function testIsNotPresent(): void
    {
        $optional = Optional::empty();
        $res = $optional->isPresent();

        $this->assertFalse($res);
    }

    public function testFilterKeep(): void
    {
        /** @var string */
        $value = 'qwe';
        $callback = fn (string $item): bool => $item === $value;

        $optional = Optional::of($value)->filter($callback);
        $isPresent = $optional->isPresent();
        $res = $optional->get();

        $this->assertTrue(
            $isPresent,
            'filter must keep items by condition'
        );
        $this->assertSame(
            $value,
            $res,
            'optional after filtering must return kept item'
        );
    }

    public function testFilterRemove(): void
    {
        $value = 'qweqwe';
        $callback = fn (string $item): bool => $item !== $value;

        $optional = Optional::of($value)->filter($callback);
        $res = $optional->isPresent();

        $this->assertFalse($res);
    }

    public function testIfPresent(): void
    {
        /** @var string */
        $value = 'qwe';
        $res = null;
        $callback = function (string $item) use (&$res): void {
            $res = $item;
        };

        Optional::of($value)->ifPresent($callback);

        $this->assertSame($value, $res);
    }

    public function testIfNotPresent(): void
    {
        $res = null;
        $callback = function (mixed $item) use (&$res): void {
            $res = $item;
        };

        Optional::empty()->ifPresent($callback);

        $this->assertNull($res);
    }

    public function testOrElse(): void
    {
        $other = 'other';

        $result = Optional::empty()->orElse($other);

        $this->assertSame($other, $result);
    }

    public function testNotOrElse(): void
    {
        /** @var float */
        $value = 1.1;
        /** @var float */
        $other = 2.2;

        $result = Optional::of($value)->orElse($other);

        $this->assertSame($value, $result);
    }

    public function testOrElseGet(): void
    {
        $other = 'other';
        $callback = fn (): string => $other;

        $result = Optional::empty()->orElseGet($callback);

        $this->assertSame($other, $result);
    }

    public function testNotOrElseGet(): void
    {
        /** @var string */
        $value = 'value';
        /** @var string */
        $other = 'other';
        $callback = fn (): string => $other;

        $result = Optional::of($value)->orElseGet($callback);

        $this->assertSame($value, $result);
    }

    public function testOrElseThrow(): void
    {
        $callback = fn (): \Throwable => new \RuntimeException();

        $this->expectException(\RuntimeException::class);
        Optional::empty()->orElseThrow($callback);
    }

    public function testOrElseNotThrow(): void
    {
        /** @var string */
        $value = 'value';
        $callback = fn (): \Throwable => new \RuntimeException();

        $result = Optional::of($value)->orElseThrow($callback);

        $this->assertSame($value, $result);
    }
}
