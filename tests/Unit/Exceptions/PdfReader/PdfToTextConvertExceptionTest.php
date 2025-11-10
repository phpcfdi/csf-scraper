<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Exceptions\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\PdfToTextConvertException;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class PdfToTextConvertExceptionTest extends TestCase
{
    public function test_implements_library_exception_type(): void
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        $this->assertTrue(is_a(PdfToTextConvertException::class, CsfScraperException::class, true));
    }

    public function test_properties(): void
    {
        $message = 'exception message';
        $command = [__DIR__, 'argument'];
        $exitCode = 1;
        $output = 'output message';
        $error = 'error message';

        $exception = new PdfToTextConvertException($message, $command, $exitCode, $output, $error);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($command, $exception->getCommand());
        $this->assertSame($exitCode, $exception->getExitCode());
        $this->assertSame($output, $exception->getOutput());
        $this->assertSame($error, $exception->getError());
    }
}
