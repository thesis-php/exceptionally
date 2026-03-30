# Thesis Exceptionally

A tiny PHP library that converts errors to exceptions.

## Installation

```shell
composer require thesis/exceptionally
```

Requires PHP 8.3+.

## Usage

```php
use function Thesis\exceptionally;

// Converts PHP errors to ErrorException
$content = exceptionally(static fn() => file_get_contents('/path/to/file'));
```

Many PHP functions trigger errors instead of throwing exceptions. This makes error handling inconsistent and cumbersome.
`exceptionally()` wraps a callable and converts any triggered error into a native [ErrorException](https://php.net/manual/en/errorexception.construct.php).

### Error suppression operator

Errors suppressed with `@` are not converted to exceptions:

```php
// Does not throw — the error is suppressed
$content = exceptionally(static fn() => @file_get_contents('/path/to/file'));
```

### Filtering by error level

By default, all levels except `E_DEPRECATED` and `E_USER_DEPRECATED` are converted.
Deprecations typically come from third-party libraries and should not interrupt execution.

You can specify which levels to convert:

```php
// Convert notices
$result = exceptionally($function, E_USER_NOTICE);

// Convert warnings and notices
$result = exceptionally($function, E_USER_WARNING | E_USER_NOTICE);

// Convert all, including deprecations
$result = exceptionally($function, E_ALL);
```

## License

MIT
