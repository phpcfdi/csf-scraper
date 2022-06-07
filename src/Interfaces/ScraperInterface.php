<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Interfaces;

use PhpCfdi\CsfScraper\PersonaFisica;
use PhpCfdi\CsfScraper\PersonaMoral;
use PhpCfdi\Rfc\Rfc;

interface ScraperInterface
{
    public function obtainFromRfcAndCif(Rfc $rfc, string $idCIF): PersonaMoral|PersonaFisica;
    public function obtainFromPdfPath(string $path): PersonaMoral|PersonaFisica;
}
