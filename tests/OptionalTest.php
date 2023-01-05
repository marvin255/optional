<?php

declare(strict_types=1);

namespace Marvin255\Optional\Tests;

use Marvin255\Optional\NoSuchElementException;
use Marvin255\Optional\Optional;

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

    public function testOfWithNull(): void
    {
        $this->expectException(\InvalidArgumentException::class);
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

        $this->assertFalse($optional->isPresent());
    }

    public function testOfNullableNotNull(): void
    {
        $value = 'qwe';
        $optional = Optional::ofNullable($value);

        $this->assertTrue($optional->isPresent());
        $this->assertSame($value, $optional->get());
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

        $result = Optional::empty()->orElseGet(fn (): string => $other);

        $this->assertSame($other, $result);
    }

    public function testNotOrElseGet(): void
    {
        /** @var string */
        $value = 'value';
        /** @var string */
        $other = 'other';

        $result = Optional::of($value)->orElseGet(fn (): string => $other);

        $this->assertSame($value, $result);
    }

    public function testOrElseThrow(): void
    {
        $this->expectException(\RuntimeException::class);
        Optional::empty()->orElseThrow(fn (): \Throwable => new \RuntimeException());
    }

    public function testOrElseNotThrow(): void
    {
        /** @var string */
        $value = 'value';

        $result = Optional::of($value)->orElseThrow(fn (): \Throwable => new \RuntimeException());

        $this->assertSame($value, $result);
    }
}
