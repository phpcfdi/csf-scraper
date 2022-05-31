<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\PdfReader\CsfExtractor;

use PhpCfdi\CsfScraper\PdfReader\CsfExtractor;
use PhpCfdi\CsfScraper\Tests\TestCase;

class GetCifIdTest extends TestCase
{
    public function test_obtains_cif(): void
    {
        $csfExtractor = new CsfExtractor($this->fileContents('csf-content.txt'));
        $this->assertSame('12345678', $csfExtractor->getCifId());
    }

    public function test_bad_content_returns_empty_rfc(): void
    {
        $csfExtractor = new CsfExtractor($this->fileContents('csf-bad-content.txt'));
        $cifId = $csfExtractor->getCifId();
        $this->assertNull($cifId);
    }
}
