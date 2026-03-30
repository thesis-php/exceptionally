<?php

declare(strict_types=1);

namespace Thesis;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\exceptionally')]
final class ExceptionallyTest extends TestCase
{
    #[TestWith([E_USER_NOTICE])]
    #[TestWith([E_USER_WARNING])]
    #[WithoutErrorHandler]
    public function testItThrowsErrors(int $level): void
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

    #[WithoutErrorHandler]
    #[DoesNotPerformAssertions]
    public function testItDoesNotThrowDeprecationsByDefault(): void
    {
        $previousErrorReportingLevel = error_reporting(0);

        try {
            exceptionally(static function (): void {
                trigger_error('Message', E_USER_DEPRECATED);
            });
        } finally {
            error_reporting($previousErrorReportingLevel);
        }
    }

    #[WithoutErrorHandler]
    public function testItThrowsDeprecationIfConfiguredExplicitly(): void
    {
        $this->expectException(\ErrorException::class);

        exceptionally(static function (): void {
            trigger_error('Message', E_USER_DEPRECATED);
        }, E_USER_DEPRECATED);
    }

    #[WithoutErrorHandler]
    public function testItThrowsNoMatterWhatErrorReportingLevel(): void
    {
        $this->expectException(\ErrorException::class);

        $previousErrorReportingLevel = error_reporting(0);

        try {
            exceptionally(static function (): void {
                trigger_error('Message', E_USER_WARNING);
            });
        } finally {
            error_reporting($previousErrorReportingLevel);
        }
    }

    #[WithoutErrorHandler]
    #[DoesNotPerformAssertions]
    public function testItIgnoresErrorLevelsOutsideConfigured(): void
    {
        $previousErrorReportingLevel = error_reporting(0);

        try {
            exceptionally(static function (): void {
                trigger_error('Message', E_USER_WARNING);
            }, E_USER_NOTICE);
        } finally {
            error_reporting($previousErrorReportingLevel);
        }
    }

    #[WithoutErrorHandler]
    public function testItThrowsSuppressedErrors(): void
    {
        $this->expectException(\ErrorException::class);

        exceptionally(static function (): void {
            @trigger_error('Message', E_USER_WARNING);
        });
    }
}
