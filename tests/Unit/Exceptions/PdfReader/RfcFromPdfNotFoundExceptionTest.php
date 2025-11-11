<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Exceptions\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\RfcFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class RfcFromPdfNotFoundExceptionTest extends TestCase
{
    public function test_implements_library_exception_type(): void
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        $this->assertTrue(is_a(RfcFromPdfNotFoundException::class, CsfScraperException::class, true));
    }
}
