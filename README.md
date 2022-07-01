# Optional

PHP implementation of Java's Optional object.

A container object which may or may not contain a non-null value. If a value is present, isPresent() will return true and get() will return the value.



## Installation

Install via composer:

```bash
composer req marvin255/optional
```



## Usage

```php
use Marvin255\Optional\Optional;

$optional = Optional::of($input);
if ($otional->isPresent()) {
    $value = $optional->get();
    // do something
}
```

With lambda

```php
use Marvin255\Optional\Optional;

Optional::of($input)->ifPresent(fn ($item): void {/* do something */});
```