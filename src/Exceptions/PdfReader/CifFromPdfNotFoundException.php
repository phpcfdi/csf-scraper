<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Exceptions\PdfReader;

use Exception;
use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;

final class CifFromPdfNotFoundException extends Exception implements CsfScraperException
{
}
