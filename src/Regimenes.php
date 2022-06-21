<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use JsonSerializable;

class Regimenes implements JsonSerializable
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

    public function addRegimen(Regimen $regimen, Regimen ...$regimenes): void
    {
        $this->regimenes[] = $regimen;
        foreach ($regimenes as $reg) {
            $this->regimenes[] = $reg;
        }
    }

    /**
     * @return array<int, array{regimen: string, regimen_id: string, fecha_alta: ?\DateTimeImmutable}>
     */
    public function toArray(): array
    {
        return array_map(
            fn (Regimen $regimen): array => $regimen->toArray(),
            $this->regimenes
        );
    }

    /**
      * @return array<int, array{regimen: string, regimen_id: string, fecha_alta: ?\DateTimeImmutable}>
      */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
