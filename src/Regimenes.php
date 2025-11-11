<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;
use JsonSerializable;

class Regimenes implements JsonSerializable
{
    /** @var list<Regimen> */
    private array $regimenes = [];

    /** @return list<Regimen> */
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

    /** @return list<array{regimen: string, regimen_id: string, fecha_alta: ?DateTimeImmutable}> */
    public function toArray(): array
    {
        return array_map(
            fn (Regimen $regimen): array => $regimen->toArray(),
            $this->regimenes,
        );
    }

    /** @return mixed[] */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
