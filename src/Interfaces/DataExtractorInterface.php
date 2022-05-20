<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Interfaces;

use PhpCfdi\CsfScraper\PersonaFisica;
use PhpCfdi\CsfScraper\PersonaMoral;

interface DataExtractorInterface
{
    public function extract(bool $isFisica): PersonaMoral|PersonaFisica;
}
