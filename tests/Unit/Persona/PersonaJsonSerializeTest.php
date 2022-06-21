<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Persona;

use PhpCfdi\CsfScraper\Persona;
use PhpCfdi\CsfScraper\PersonaFisica;
use PhpCfdi\CsfScraper\PersonaMoral;
use PhpCfdi\CsfScraper\Tests\TestCase;

class PersonaJsonSerializeTest extends TestCase
{
    public function test_serialize_default_person(): void
    {
        $person = new Persona();

        /** @var string $json */
        $json = json_encode($person);

        $this->assertSame([
            'fecha_inicio_operaciones' => null,
            'situacion_contribuyente' => '',
            'fecha_ultimo_cambio_situacion' => null,
            'entidad_federativa' => '',
            'municipio_delegacion' => '',
            'colonia' => '',
            'tipo_vialidad' => '',
            'nombre_vialidad' => '',
            'numero_exterior' => '',
            'numero_interior' => '',
            'codigo_postal' => '',
            'correo_electronico' => '',
            'al' => '',
            'regimenes' => [],
            'extra_data' => [],
        ], json_decode($json, associative: true));
    }

    public function test_serialize_default_person_moral(): void
    {
        $person = new PersonaMoral();

        /** @var string $json */
        $json = json_encode($person);

        $this->assertSame([
            'razon_social' => '',
            'regimen_de_capital' => '',
            'fecha_constitucion' => null,
            'fecha_inicio_operaciones' => null,
            'situacion_contribuyente' => '',
            'fecha_ultimo_cambio_situacion' => null,
            'entidad_federativa' => '',
            'municipio_delegacion' => '',
            'colonia' => '',
            'tipo_vialidad' => '',
            'nombre_vialidad' => '',
            'numero_exterior' => '',
            'numero_interior' => '',
            'codigo_postal' => '',
            'correo_electronico' => '',
            'al' => '',
            'regimenes' => [],
            'extra_data' => [],
        ], json_decode($json, associative: true));
    }

    public function test_serialize_default_person_fisica(): void
    {
        $person = new PersonaFisica();

        /** @var string $json */
        $json = json_encode($person);

        $this->assertSame([
            'curp' => '',
            'nombre' => '',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'fecha_nacimiento' => null,
            'fecha_inicio_operaciones' => null,
            'situacion_contribuyente' => '',
            'fecha_ultimo_cambio_situacion' => null,
            'entidad_federativa' => '',
            'municipio_delegacion' => '',
            'colonia' => '',
            'tipo_vialidad' => '',
            'nombre_vialidad' => '',
            'numero_exterior' => '',
            'numero_interior' => '',
            'codigo_postal' => '',
            'correo_electronico' => '',
            'al' => '',
            'regimenes' => [],
            'extra_data' => [],
        ], json_decode($json, associative: true));
    }
}
