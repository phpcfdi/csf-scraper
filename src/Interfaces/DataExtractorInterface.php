<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Interfaces;

interface DataExtractorInterface
{
    /**
     * @return array<int|string, array<int|string, mixed>|string>
     */
    public function extract(bool $isFisica): array;
}
