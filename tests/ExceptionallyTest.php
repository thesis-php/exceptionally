<?php

declare(strict_types=1);

namespace Thesis;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\exceptionally')]
final class ExceptionallyTest extends TestCase
{
    #[TestWith([E_USER_DEPRECATED])]
    #[TestWith([E_USER_NOTICE])]
    #[TestWith([E_USER_WARNING])]
    public function test(int $level): void
    {
        try {
            exceptionally(static function () use ($level): void {
                trigger_error('Message', $level);
            });
        } catch (\ErrorException $exception) {
            self::assertSame('Message', $exception->getMessage());
            self::assertSame(0, $exception->getCode());
            self::assertSame(__LINE__ - 5, $exception->getLine());
            self::assertSame(__FILE__, $exception->getFile());
            self::assertSame($level, $exception->getSeverity());
            self::assertNull($exception->getPrevious());
        }
    }

    #[DoesNotPerformAssertions]
    public function testExactErrorLevel(): void
    {
        exceptionally(static function (): void {
            trigger_error('Message', E_USER_WARNING);
        }, E_USER_NOTICE);
    }
}
