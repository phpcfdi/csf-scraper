<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;

class Regimen
{
    private string $regimen = '';
    private ?DateTimeImmutable $fechaAlta = null;

    public function getRegimen(): string
    {
        return $this->regimen;
    }

    public function getFechaAlta(): ?DateTimeImmutable
    {
        return $this->fechaAlta;
    }

    public function setRegimen(string $regimen): void
    {
        $this->regimen = $regimen;
    }

    public function setFechaAlta(string $fechaAlta): void
    {
        $date = DateTimeImmutable::createFromFormat('d-m-Y', $fechaAlta);
        $this->fechaAlta = false !== $date ? $date : null;
    }
}
