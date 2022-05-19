<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PhpCfdi\CsfScraper\Scraper;
use PhpCfdi\CsfScraper\Tests\TestCase;

class ScrapCsfTest extends TestCase
{
    public function test_scrap_from_idcif_and_rfc_by_moral(): void
    {
        $mock = new MockHandler([
            new Response(200, [], $this->fileContents('scrap_moral.html')),
        ]);
        $rfc = 'AAA010101AAA';
        $idcif = '1904014102123';
        $client = new Client([
            'handler' => $mock,
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
        $csfScrap = new Scraper($client);
        $expectedData = [
            'razon_social' => 'Mi razón social',
            'regimen_de_capital' => 'SA DE CV',
            'fecha_constitucion' => '21-02-2019',
            'fecha_inicio_operaciones' => '21-02-2019',
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => '21-02-2019',
            'entidad_federativa' => 'CIUDAD DE MEXICO',
            'municipio_delegacion' => 'CUAUHTEMOC',
            'colonia' => 'CUAUHTEMOC',
            'tipo_vialidad' => 'Tipo vialidad',
            'nombre_vialidad' => 'PASEO DE LA REFORMA',
            'numero_exterior' => '143',
            'numero_interior' => 'Piso 69',
            'codigo_postal' => '72055',
            'correo_electronico' => 'example@example.com',
            'al' => 'CIUDAD DE MEXICO 2',
            'regimenes' => [
                [
                    'regimen' => 'Régimen General de Ley Personas Morales',
                    'fecha_alta' => '21-02-2019',
                ],
            ],
        ];

        $data = $csfScrap->data($rfc, $idcif);

        $this->assertSame($expectedData, $data);
    }

    public function test_scrap_from_idcif_and_rfc_by_fisica(): void
    {
        $mock = new MockHandler([
            new Response(200, [], $this->fileContents('scrap_fisica.html')),
        ]);
        $rfc = 'AAA010101AAAA';
        $idcif = '21030201234';
        $client = new Client([
            'handler' => $mock,
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
        $csfScrap = new Scraper($client);
        $expectedData = [
            'curp' => 'CURP',
            'nombre' => 'JUAN',
            'apellido_paterno' => 'PEREZ',
            'apellido_materno' => 'PEREZ',
            'fecha_nacimiento' => '01-05-1973',
            'fecha_inicio_operaciones' => '03-11-2004',
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => '03-11-2004',
            'entidad_federativa' => 'CIUDAD DE MEXICO',
            'municipio_delegacion' => 'IZTAPALAPA',
            'colonia' => 'MI COLONIA',
            'tipo_vialidad' => 'CALLE',
            'nombre_vialidad' => 'BENITO JUAREZ',
            'numero_exterior' => '183',
            'numero_interior' => '',
            'codigo_postal' => '72000',
            'correo_electronico' => '',
            'al' => 'CIUDAD DE MEXICO 3',
            'regimenes' => [
                [
                    'regimen' => 'Régimen de Incorporación Fiscal',
                    'fecha_alta' => '01-01-2014',
                ],
            ],
        ];

        $data = $csfScrap->data($rfc, $idcif);

        $this->assertSame($expectedData, $data);
    }

    public function test_scrap_from_idcif_and_rfc_multiple_regimen(): void
    {
        $mock = new MockHandler([
            new Response(200, [], $this->fileContents('scrap_regimenes.html')),
        ]);
        $rfc = 'AAA010101AAAA';
        $idcif = '18080516917';
        $client = new Client([
            'handler' => $mock,
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
        $csfScrap = new Scraper($client);
        $expectedData = [
            'curp' => 'CURP',
            'nombre' => 'JUAN',
            'apellido_paterno' => 'PEREZ',
            'apellido_materno' => 'PEREZ',
            'fecha_nacimiento' => '21-07-1995',
            'fecha_inicio_operaciones' => '01-01-2018',
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => '16-08-2018',
            'entidad_federativa' => 'QUERETARO',
            'municipio_delegacion' => 'MUNICIPIO',
            'colonia' => 'MI COLONIA',
            'tipo_vialidad' => 'CALZADA (CALZ.)',
            'nombre_vialidad' => 'DEL BOSQUE',
            'numero_exterior' => '19',
            'numero_interior' => '',
            'codigo_postal' => '72000',
            'correo_electronico' => 'example@example.com',
            'al' => 'QUERETARO 1',
            'regimenes' => [
                [
                    'regimen' => 'Régimen de Sueldos y Salarios e Ingresos Asimilados a Salarios',
                    'fecha_alta' => '01-01-2018',
                ],
                [
                    'regimen' => 'Régimen Simplificado de Confianza',
                    'fecha_alta' => '09-02-2022',
                ],
            ],
        ];

        $data = $csfScrap->data($rfc, $idcif);

        $this->assertSame($expectedData, $data);
    }

    public function test_return_empty_when_not_found(): void
    {
        $mock = new MockHandler([
            new Response(200, [], $this->fileContents('error.html')),
        ]);
        $rfc = 'AAA010101AAA';
        $idcif = '1904014102123';
        $client = new Client([
            'handler' => $mock,
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
        $expectedData = [];
        $csfScrap = new Scraper($client);

        $data = $csfScrap->data($rfc, $idcif);

        $this->assertSame($expectedData, $data);
    }
}
