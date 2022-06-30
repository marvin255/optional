<?php

declare(strict_types=1);

namespace Marvin255\Optional\Tests;

use Marvin255\Optional\NoSuchElementException;
use Marvin255\Optional\Optional;
use RuntimeException;
use Throwable;

/**
 * @internal
 */
class OptionalTest extends BaseCase
{
    public function testOf(): void
    {
        $optional = Optional::of('test');

        $this->assertInstanceOf(Optional::class, $optional);
    }

    public function testEmpty(): void
    {
        $optional = Optional::empty();

        $this->assertInstanceOf(Optional::class, $optional);
    }

    public function testGet(): void
    {
        $value = 'test';

        $optional = Optional::of($value);

        $this->assertSame($value, $optional->get());
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

        $this->assertTrue($optional->isPresent());
    }

    public function testIsNotPresent(): void
    {
        $optional = Optional::empty();

        $this->assertFalse($optional->isPresent());
    }

    public function testFilterOk(): void
    {
        $value = 'qwe';

        $optional = Optional::of($value)->filter(fn (string $item) => $item === $value);

        $this->assertTrue($optional->isPresent());
        $this->assertSame($value, $optional->get());
    }

    public function testFilterNotOk(): void
    {
        $value = 'qweqwe';

        $optional = Optional::of($value)->filter(fn (string $item) => $item !== $value);

        $this->assertFalse($optional->isPresent());
    }

    public function testIfPresent(): void
    {
        $res = false;

        Optional::of('qwe')->ifPresent(
            function (string $item) use (&$res): void {
                $res = $item === 'qwe';
            }
        );

        $this->assertTrue($res);
    }

    public function testIfNotPresent(): void
    {
        $res = false;

        Optional::empty()->ifPresent(
            function (mixed $item) use (&$res): void {
                $res = true;
            }
        );

        $this->assertFalse($res);
    }

    public function testOrElse(): void
    {
        $other = 'other';

        $result = Optional::empty()->orElse($other);

        $this->assertSame($other, $result);
    }

    public function testNotOrElse(): void
    {
        $value = 1;
        $other = 2;

        $result = Optional::of($value)->orElse($other);

        $this->assertSame($value, $result);
    }

    public function testOrElseGet(): void
    {
        $other = 'other';

        $result = Optional::empty()->orElseGet(fn (): string => $other);

        $this->assertSame($other, $result);
    }

    public function testNotOrElseGet(): void
    {
        $value = 'value';
        $other = 'other';

        $result = Optional::of($value)->orElseGet(fn (): string => $other);

        $this->assertSame($value, $result);
    }

    public function testOrElseThrow(): void
    {
        $this->expectException(RuntimeException::class);
        Optional::empty()->orElseThrow(fn (): Throwable => new RuntimeException());
    }

    public function testOrElseNotThrow(): void
    {
        $value = 'value';

        $result = Optional::of($value)->orElseThrow(fn (): Throwable => new RuntimeException());

        $this->assertSame($value, $result);
    }
}
