<?php

declare(strict_types=1);

namespace Marvin255\Optional;

/**
 * Exception that will be thrown if optional doesn't content the value.
 *
 * @psalm-api
 */
final class NoSuchElementException extends OptionalException
{
}
