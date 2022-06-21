<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;

class Regimenes
{
    /**
     * @var Regimen[]
     */
    private array $regimenes = [];

    /**
     *
     * @return Regimen[]
     */
    public function getRegimenes(): array
    {
        return $this->regimenes;
    }

    public function addRegimen(Regimen $regimen): void
    {
        $this->regimenes[] = $regimen;
    }

    public function addMultiRegimen(Regimen ...$regimenes): void
    {
        foreach ($regimenes as $regimen) {
            $this->addRegimen($regimen);
        }
    }

    /**
     * @return array<int, array{regimen: string, regimen_id: string, fecha_alta: ?DateTimeImmutable}>
     */
    public function toArray(): array
    {
        return array_map(
            fn (Regimen $regimen): array => $regimen->toArray(),
            $this->regimenes
        );
    }
}
