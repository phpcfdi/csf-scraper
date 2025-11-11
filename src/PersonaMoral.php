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

    /** @return array<string, string> */
    public function getTdTitlesTextToSearch(): array
    {
        return [
            'RazonSocial' => 'Denominación o Razón Social:',
            'RegimenDeCapital' => 'Régimen de capital:',
            'FechaConstitucion' => 'Fecha de constitución:',
            'FechaInicioOperaciones' => 'Fecha de Inicio de operaciones:',
            'SituacionContribuyente' => 'Situación del contribuyente:',
            'FechaUltimoCambioSituacion' => 'Fecha del último cambio de situación:',
            'EntidadFederativa' => 'Entidad Federativa:',
            'MunicipioDelegacion' => 'Municipio o delegación:',
            'Colonia' => 'Colonia:',
            'Localidad' => 'Localidad:',
            'TipoVialidad' => 'Tipo de vialidad:',
            'NombreVialidad' => 'Nombre de la vialidad:',
            'NumeroExterior' => 'Número exterior:',
            'NumeroInterior' => 'Número interior:',
            'CodigoPostal' => 'CP:',
            'CorreoElectronico' => 'Correo electrónico:',
            'Al' => 'AL:',
        ];
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
