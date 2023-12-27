<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;

class PersonaFisica extends Persona
{
    private string $curp = '';

    private string $nombre = '';

    private string $apellidoPaterno = '';

    private string $apellidoMaterno = '';

    private ?DateTimeImmutable $fechaNacimiento = null;

    public function getCurp(): string
    {
        return $this->curp;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellidoPaterno(): string
    {
        return $this->apellidoPaterno;
    }

    public function getApellidoMaterno(): string
    {
        return $this->apellidoMaterno;
    }

    public function getFechaNacimiento(): ?DateTimeImmutable
    {
        return $this->fechaNacimiento;
    }

    public function setCurp(string $curp): void
    {
        $this->curp = $curp;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellidoPaterno(string $apellidoPaterno): void
    {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function setApellidoMaterno(string $apellidoMaterno): void
    {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function setFechaNacimiento(string $fechaNacimiento): void
    {
        $date = DateTimeImmutable::createFromFormat('!d-m-Y', $fechaNacimiento);
        $this->fechaNacimiento = false !== $date ? $date : null;
    }

    public function getKeyNameByIndex(int $index): ?string
    {
        return match ($index) {
            2 => 'Curp',
            4 => 'Nombre',
            6 => 'ApellidoPaterno',
            8 => 'ApellidoMaterno',
            10 => 'FechaNacimiento',
            12 => 'FechaInicioOperaciones',
            14 => 'SituacionContribuyente',
            16 => 'FechaUltimoCambioSituacion',
            21 => 'EntidadFederativa',
            23 => 'MunicipioDelegacion',
            25 => 'Colonia',
            27 => 'TipoVialidad',
            29 => 'NombreVialidad',
            31 => 'NumeroExterior',
            33 => 'NumeroInterior',
            35 => 'CodigoPostal',
            37 => 'CorreoElectronico',
            39 => 'Al',
            default => null
        };
    }

    /**
     *
     * @return array<string, string>
     */
    public function getTdTitlesTextToSearch(): array
    {
        return [
            'Curp' => 'CURP:',
            'Nombre' => 'Nombre:',
            'ApellidoPaterno' => 'Apellido Paterno:',
            'ApellidoMaterno' => 'Apellido Materno:',
            'FechaNacimiento' => 'Fecha Nacimiento:',
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
            'curp' => $this->getCurp(),
            'nombre' => $this->getNombre(),
            'apellido_paterno' => $this->getApellidoPaterno(),
            'apellido_materno' => $this->getApellidoMaterno(),
            'fecha_nacimiento' => $this->getFechaNacimiento(),
        ], $array);
    }
}
