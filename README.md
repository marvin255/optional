# Optional

[![Latest Stable Version](https://poser.pugx.org/marvin255/optional/v)](https://packagist.org/packages/marvin255/optional)
[![Total Downloads](https://poser.pugx.org/marvin255/optional/downloads)](https://packagist.org/packages/marvin255/optional)
[![License](https://poser.pugx.org/marvin255/optional/license)](https://packagist.org/packages/marvin255/optional)
[![Build Status](https://github.com/marvin255/optional/workflows/marvin255_optional/badge.svg)](https://github.com/marvin255/optional/actions?query=workflow%3A%22marvin255_optional%22)

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
if ($optional->isPresent()) {
    $value = $optional->get();
    // do something
}
```

With lambda

```php
use Marvin255\Optional\Optional;

Optional::of($input)->ifPresent(function ($item): void {/* do something */});
```
