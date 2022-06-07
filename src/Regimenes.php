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
     *
     * @return array<int, array<string, string|DateTimeImmutable|null>>
     */
    public function toArray(): array
    {
        $regimenArray = [];
        $regimenesArray = [];
        foreach ($this->getRegimenes() as $regimen) {
            $regimenArray['regimen'] = $regimen->getRegimen();
            $regimenArray['regimen_id'] = $regimen->getRegimenId();
            $regimenArray['fecha_alta'] = $regimen->getFechaAlta();
            $regimenesArray[] = $regimenArray;
        }
        return $regimenesArray;
    }
}
