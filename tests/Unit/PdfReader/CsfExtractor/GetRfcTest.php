<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\PdfReader\CsfExtractor;

use PhpCfdi\CsfScraper\Exceptions\PdfReader\EmptyPdfContentException;
use PhpCfdi\CsfScraper\PdfReader\CsfExtractor;
use PhpCfdi\CsfScraper\Tests\TestCase;

class GetRfcTest extends TestCase
{
    public function test_obtains_rfc(): void
    {
        $csfExtractor = new CsfExtractor($this->fileContents('csf-content.txt'));
        $this->assertSame('DIM8701081LA', $csfExtractor->getRfc());
    }

    public function test_bad_content_returns_empty_rfc(): void
    {
        $csfExtractor = new CsfExtractor($this->fileContents('csf-bad-content.txt'));
        $rfc = $csfExtractor->getRfc();
        $this->assertNull($rfc);
    }

    public function test_cannot_read_content(): void
    {
        $this->expectException(EmptyPdfContentException::class);
        new CsfExtractor('');
    }
}
