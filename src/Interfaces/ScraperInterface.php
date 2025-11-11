<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Interfaces;

use PhpCfdi\CsfScraper\PersonaFisica;
use PhpCfdi\CsfScraper\PersonaMoral;
use PhpCfdi\Rfc\Rfc;

interface ScraperInterface
{
    /** Obtain a PersonaMoral or PersonaFisica object with the information from SAT website */
    public function obtainFromRfcAndCif(Rfc $rfc, string $idCIF): PersonaMoral|PersonaFisica;

    /**
     * Obtain a PersonaMoral or PersonaFisica object with the information from SAT website
     * The RFC and CIF is taken from the "Constancia de Situación Fiscal" PDF file
     */
    public function obtainFromPdfPath(string $path): PersonaMoral|PersonaFisica;
}
