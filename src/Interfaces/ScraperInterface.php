<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Interfaces;

interface ScraperInterface
{
    /**
     *
     * @return array<int|string, array<int|string, mixed>|string>
     */
    public function data(string $rfc, string $idCIF): array;
}
