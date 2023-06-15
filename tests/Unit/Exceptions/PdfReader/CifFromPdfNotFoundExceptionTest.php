<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Exceptions\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\CifFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class CifFromPdfNotFoundExceptionTest extends TestCase
{
    public function test_implements_library_exception_type(): void
    {
        $this->assertTrue(is_a(CifFromPdfNotFoundException::class, CsfScraperException::class, true));
    }
}
