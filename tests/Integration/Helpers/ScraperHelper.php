<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Integration\Helpers;

use PhpCfdi\CsfScraper\Scraper;
use PhpCfdi\CsfScraper\Tests\TestCase;

class ScraperHelper extends Scraper
{
    protected function pdfToTextContent(string $path): string
    {
        return TestCase::fileContents('csf-content.txt');
    }
}
