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

### Filtering by error level

By default, all error levels are caught. You can specify which levels to convert:

```php
// Only convert notices
$result = exceptionally($callback, E_USER_NOTICE);

// Convert warnings and notices
$result = exceptionally($callback, E_USER_WARNING | E_USER_NOTICE);
```

## License

MIT
