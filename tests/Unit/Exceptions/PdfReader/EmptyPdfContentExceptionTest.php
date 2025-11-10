<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Exceptions\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\EmptyPdfContentException;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class EmptyPdfContentExceptionTest extends TestCase
{
    public function test_implements_library_exception_type(): void
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        $this->assertTrue(is_a(EmptyPdfContentException::class, CsfScraperException::class, true));
    }
}
