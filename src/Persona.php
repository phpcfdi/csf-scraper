<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;
use JsonSerializable;

class Persona implements JsonSerializable
{
    private ?DateTimeImmutable $fechaInicioOperaciones = null;
    private string $rfc = '';
    private string $idCif = '';
    private string $situacionContribuyente = '';
    private ?DateTimeImmutable $fechaUltimoCambioSituacion = null;
    private string $entidadFederativa = '';
    private string $municipioDelegacion = '';
    private string $colonia = '';
    private string $tipoVialidad = '';
    private string $nombreVialidad = '';
    private string $numeroExterior = '';
    private string $numeroInterior = '';
    private string $codigoPostal = '';
    private string $correoElectronico = '';
    private string $al = '';
    private Regimenes $regimenes;
    /** @var string[] */
    private array $data = [];

    public function __construct()
    {
        $this->regimenes = new Regimenes();
    }

    public function getRfc(): string
    {
        return $this->rfc;
    }

    public function getIdCif(): string
    {
        return $this->idCif;
    }

    public function getFechaInicioOperaciones(): ?DateTimeImmutable
    {
        return $this->fechaInicioOperaciones;
    }

    public function getSituacionContribuyente(): string
    {
        return $this->situacionContribuyente;
    }

    public function getfechaUltimoCambioSituacion(): ?DateTimeImmutable
    {
        return $this->fechaUltimoCambioSituacion;
    }

    public function getEntidadFederativa(): string
    {
        return $this->entidadFederativa;
    }

    public function getMunicipioDelegacion(): string
    {
        return $this->municipioDelegacion;
    }

    public function getColonia(): string
    {
        return $this->colonia;
    }

    public function getTipoVialidad(): string
    {
        return $this->tipoVialidad;
    }

    public function getNombreVialidad(): string
    {
        return $this->nombreVialidad;
    }

    public function getNumeroExterior(): string
    {
        return $this->numeroExterior;
    }

    public function getNumeroInterior(): string
    {
        return $this->numeroInterior;
    }

    public function getCodigoPostal(): string
    {
        return $this->codigoPostal;
    }

    public function getCorreoElectronico(): string
    {
        return $this->correoElectronico;
    }

    public function getAl(): string
    {
        return $this->al;
    }

    public function getRegimenes(): Regimenes
    {
        return $this->regimenes;
    }

    public function setRfc(string $rfc): void
    {
        $this->rfc = $rfc;
    }

    public function setIdCif(string $idCif): void
    {
        $this->idCif = $idCif;
    }

    public function setFechaInicioOperaciones(string $fechaInicioOperaciones): void
    {
        $date = DateTimeImmutable::createFromFormat('!d-m-Y', $fechaInicioOperaciones);
        $this->fechaInicioOperaciones = false !== $date ? $date : null;
    }

    public function setSituacionContribuyente(string $situacionContribuyente): void
    {
        $this->situacionContribuyente = $situacionContribuyente;
    }

    public function setfechaUltimoCambioSituacion(string $fechaUltimoCambioSituacion): void
    {
        $date = DateTimeImmutable::createFromFormat('!d-m-Y', $fechaUltimoCambioSituacion);
        $this->fechaUltimoCambioSituacion = false !== $date ? $date : null;
    }

    public function setEntidadFederativa(string $entidadFederativa): void
    {
        $this->entidadFederativa = $entidadFederativa;
    }

    public function setMunicipioDelegacion(string $municipioDelegacion): void
    {
        $this->municipioDelegacion = $municipioDelegacion;
    }

    public function setColonia(string $colonia): void
    {
        $this->colonia = $colonia;
    }

    public function setTipoVialidad(string $tipoVialidad): void
    {
        $this->tipoVialidad = $tipoVialidad;
    }

    public function setNombreVialidad(string $nombreVialidad): void
    {
        $this->nombreVialidad = $nombreVialidad;
    }

    public function setNumeroExterior(string $numeroExterior): void
    {
        $this->numeroExterior = $numeroExterior;
    }

    public function setNumeroInterior(string $numeroInterior): void
    {
        $this->numeroInterior = $numeroInterior;
    }

    public function setCodigoPostal(string $codigoPostal): void
    {
        $this->codigoPostal = $codigoPostal;
    }

    public function setCorreoElectronico(string $correoElectronico): void
    {
        $this->correoElectronico = $correoElectronico;
    }

    public function setAl(string $al): void
    {
        $this->al = $al;
    }

    public function addRegimenes(Regimen ...$regimenes): void
    {
        $this->regimenes->addRegimen(...$regimenes);
    }

    public function __set(string $name, string $value): void
    {
        $method = "set$name";
        if (method_exists($this, $method)) {
            /** @var callable:mixed $callable */
            $callable = [$this, $method];
            call_user_func($callable, $value);
        } else {
            $this->data[$name] = $value;
        }
    }

    public function __get(string $name): mixed
    {
        if (! array_key_exists($name, $this->data)) {
            return null;
        }
        return $this->data[$name];
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'rfc' => $this->getRfc(),
            'id_cif' => $this->getIdCif(),
            'fecha_inicio_operaciones' => $this->getFechaInicioOperaciones(),
            'situacion_contribuyente' => $this->getSituacionContribuyente(),
            'fecha_ultimo_cambio_situacion' => $this->getfechaUltimoCambioSituacion(),
            'entidad_federativa' => $this->getEntidadFederativa(),
            'municipio_delegacion' => $this->getMunicipioDelegacion(),
            'colonia' => $this->getColonia(),
            'tipo_vialidad' => $this->getTipoVialidad(),
            'nombre_vialidad' => $this->getNombreVialidad(),
            'numero_exterior' => $this->getNumeroExterior(),
            'numero_interior' => $this->getNumeroInterior(),
            'codigo_postal' => $this->getCodigoPostal(),
            'correo_electronico' => $this->getCorreoElectronico(),
            'al' => $this->getAl(),
            'regimenes' => $this->regimenes->toArray(),
            'extra_data' => $this->data,
        ];
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
