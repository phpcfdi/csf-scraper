<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;

class PersonaMoral extends Persona
{
    private string $razonSocial = '';
    private string $regimenDeCapital = '';
    private ?DateTimeImmutable $fechaConstitucion = null;

    public function getRazonSocial(): string
    {
        return $this->razonSocial;
    }

    public function getRegimenDeCapital(): string
    {
        return $this->regimenDeCapital;
    }

    public function getFechaConstitucion(): ?DateTimeImmutable
    {
        return $this->fechaConstitucion;
    }

    public function setRazonSocial(string $razonSocial): void
    {
        $this->razonSocial = $razonSocial;
    }

    public function setRegimenDeCapital(string $regimenDeCapital): void
    {
        $this->regimenDeCapital = $regimenDeCapital;
    }

    public function setFechaConstitucion(string $fechaConstitucion): void
    {
        $date = DateTimeImmutable::createFromFormat('!d-m-Y', $fechaConstitucion);
        $this->fechaConstitucion = false !== $date ? $date : null;
    }

    public function getKeyNameByIndex(int $index): ?string
    {
        return match ($index) {
            2 => 'RazonSocial',
            4 => 'RegimenDeCapital',
            6 => 'FechaConstitucion',
            8 => 'FechaInicioOperaciones',
            10 => 'SituacionContribuyente',
            12 => 'FechaUltimoCambioSituacion',
            17 => 'EntidadFederativa',
            19 => 'MunicipioDelegacion',
            21 => 'Colonia',
            23 => 'TipoVialidad',
            25 => 'NombreVialidad',
            27 => 'NumeroExterior',
            29 => 'NumeroInterior',
            31 => 'CodigoPostal',
            33 => 'CorreoElectronico',
            35 => 'Al',
            default => null
        };
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        return array_merge([
            'razon_social' => $this->getRazonSocial(),
            'regimen_de_capital' => $this->getRegimenDeCapital(),
            'fecha_constitucion' => $this->getFechaConstitucion(),
        ], $array);
    }
}
