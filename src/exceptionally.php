<?php

declare(strict_types=1);

namespace Thesis;

/**
 * @api
 *
 * @template T
 * @param callable(): (false|T) $function
 * @return T
 * @throws \ErrorException
 */
function exceptionally(callable $function, int $errorLevels = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED): mixed
{
    /** @var ?\Closure(int, string, string, int): never */
    static $handler = null;

    $handler ??= static fn(int $level, string $message, string $file, int $line) => throw new \ErrorException(
        message: $message,
        severity: $level,
        filename: $file,
        line: $line,
    );

    set_error_handler($handler, $errorLevels);

    try {
        /** @var T */
        return $function();
    } finally {
        restore_error_handler();
    }
}
