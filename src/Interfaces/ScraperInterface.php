<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Interfaces;

use PhpCfdi\CsfScraper\PersonaFisica;
use PhpCfdi\CsfScraper\PersonaMoral;

interface ScraperInterface
{
    public function data(string $rfc, string $idCIF): PersonaMoral|PersonaFisica;
}
