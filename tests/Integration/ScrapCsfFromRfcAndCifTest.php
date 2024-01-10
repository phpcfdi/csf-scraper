<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Integration;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PhpCfdi\CsfScraper\Exceptions\CifDownloadException;
use PhpCfdi\CsfScraper\Exceptions\CifNotFoundException;
use PhpCfdi\CsfScraper\Scraper;
use PhpCfdi\CsfScraper\Tests\TestCase;
use PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException;
use PhpCfdi\Rfc\Rfc;

class ScrapCsfFromRfcAndCifTest extends TestCase
{
    private function prepareScraper(string $htmlResponse): Scraper
    {
        $mock = new MockHandler([
            new Response(200, [], $this->fileContents($htmlResponse)),
        ]);
        $client = new Client([
            'handler' => $mock,
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]);
        return new Scraper($client);
    }

    public function test_scrap_from_idcif_and_rfc_by_moral(): void
    {
        $csfScrap = $this->prepareScraper('scrap_moral.html');
        $rfc = 'DIM8701081LA';
        $idcif = '1904014102123';
        $expectedData = [
            'rfc' => 'DIM8701081LA',
            'id_cif' => '1904014102123',
            'razon_social' => 'Mi razón social',
            'regimen_de_capital' => 'SA DE CV',
            'fecha_constitucion' => DateTimeImmutable::createFromFormat('!d-m-Y', '21-02-2019'),
            'fecha_inicio_operaciones' => DateTimeImmutable::createFromFormat('!d-m-Y', '21-02-2019'),
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => DateTimeImmutable::createFromFormat('!d-m-Y', '21-02-2019'),
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
                    'fecha_alta' => DateTimeImmutable::createFromFormat('!d-m-Y', '21-02-2019'),
                    'regimen_id' => '601',
                ],
            ],
            'extra_data' => [
                'Localidad' => '',
            ],
        ];

        $data = $csfScrap->obtainFromRfcAndCif(Rfc::parse($rfc), $idcif)->toArray();

        $this->assertEquals($expectedData, $data);
    }

    public function test_scrap_from_idcif_and_rfc_by_fisica(): void
    {
        $csfScrap = $this->prepareScraper('scrap_fisica.html');
        $rfc = 'COSC8001137NA';
        $idcif = '1904014102123';
        $expectedData = [
            'rfc' => 'COSC8001137NA',
            'id_cif' => '1904014102123',
            'curp' => 'CURP',
            'nombre' => 'JUAN',
            'apellido_paterno' => 'PEREZ',
            'apellido_materno' => 'RODRIGUEZ',
            'fecha_nacimiento' => DateTimeImmutable::createFromFormat('!d-m-Y', '01-05-1973'),
            'fecha_inicio_operaciones' => DateTimeImmutable::createFromFormat('!d-m-Y', '03-11-2004'),
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => DateTimeImmutable::createFromFormat('!d-m-Y', '03-11-2004'),
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
                    'fecha_alta' => DateTimeImmutable::createFromFormat('!d-m-Y', '01-01-2014'),
                    'regimen_id' => '621',
                ],
            ],
            'extra_data' => [
                'Localidad' => '',
            ],
        ];

        $data = $csfScrap->obtainFromRfcAndCif(Rfc::parse($rfc), $idcif)->toArray();

        $this->assertEquals($expectedData, $data);
    }

    public function test_scrap_from_idcif_and_rfc_multiple_regimen(): void
    {
        $csfScrap = $this->prepareScraper('scrap_regimenes.html');
        $rfc = 'COSC8001137NA';
        $idcif = '1904014102123';
        $expectedData = [
            'rfc' => 'COSC8001137NA',
            'id_cif' => '1904014102123',
            'curp' => 'CURP',
            'nombre' => 'JUAN',
            'apellido_paterno' => 'PEREZ',
            'apellido_materno' => 'RODRIGUEZ',
            'fecha_nacimiento' => DateTimeImmutable::createFromFormat('!d-m-Y', '21-07-1995'),
            'fecha_inicio_operaciones' => DateTimeImmutable::createFromFormat('!d-m-Y', '01-01-2018'),
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => DateTimeImmutable::createFromFormat('!d-m-Y', '16-08-2018'),
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
                    'fecha_alta' => DateTimeImmutable::createFromFormat('!d-m-Y', '01-01-2018'),
                    'regimen_id' => '605',
                ],
                [
                    'regimen' => 'Régimen Simplificado de Confianza',
                    'fecha_alta' => DateTimeImmutable::createFromFormat('!d-m-Y', '09-02-2022'),
                    'regimen_id' => '626',
                ],
            ],
            'extra_data' => [
                'Localidad' => '',
            ],
        ];

        $data = $csfScrap->obtainFromRfcAndCif(Rfc::parse($rfc), $idcif)->toArray();

        $this->assertEquals($expectedData, $data);
    }

    public function test_scrap_from_idcif_and_rfc_localidad_instead_of_colonia(): void
    {
        $csfScrap = $this->prepareScraper('scrap_moral_without_colonia.html');
        $rfc = 'DIM8701081LA';
        $idcif = '1904014102123';
        $expectedData = [
            'rfc' => 'DIM8701081LA',
            'id_cif' => '1904014102123',
            'razon_social' => 'MI RAZON',
            'regimen_de_capital' => 'SA DE CV',
            'fecha_constitucion' => DateTimeImmutable::createFromFormat('!d-m-Y', '29-12-1997'),
            'fecha_inicio_operaciones' => DateTimeImmutable::createFromFormat('!d-m-Y', '16-01-1999'),
            'situacion_contribuyente' => 'ACTIVO',
            'fecha_ultimo_cambio_situacion' => DateTimeImmutable::createFromFormat('!d-m-Y', '16-01-1999'),
            'entidad_federativa' => 'CHIAPAS',
            'municipio_delegacion' => 'Mi Municipio',
            'colonia' => '',
            'tipo_vialidad' => 'CALLE',
            'nombre_vialidad' => 'CONOCIDO',
            'numero_exterior' => 'SIN NUMERO',
            'numero_interior' => 'SIN NUMERO',
            'codigo_postal' => '72030',
            'correo_electronico' => 'email@example.com',
            'al' => 'CHIAPAS 1',
            'regimenes' => [
                [
                    'regimen' => 'Régimen General de Ley Personas Morales',
                    'fecha_alta' => DateTimeImmutable::createFromFormat('!d-m-Y', '31-03-2001'),
                    'regimen_id' => '601',
                ],
            ],
            'extra_data' => [
                'Localidad' => '30210010',
            ],
        ];

        $data = $csfScrap->obtainFromRfcAndCif(Rfc::parse($rfc), $idcif)->toArray();

        $this->assertEquals($expectedData, $data);
    }

    public function test_return_empty_when_not_found(): void
    {
        $csfScrap = $this->prepareScraper('error.html');
        $rfc = 'COSC8001137NA';
        $idcif = '1904014102123';
        $expectedData = [];
        $this->expectException(CifNotFoundException::class);

        $data = $csfScrap->obtainFromRfcAndCif(Rfc::parse($rfc), $idcif);

        $this->assertSame($expectedData, $data);
    }

    public function test_throw_exception_on_download_error(): void
    {
        $client = new Client([
            'handler' => new MockHandler([$this->createMock(GuzzleException::class)]),
        ]);
        $csfScrap = new Scraper($client);
        $rfc = Rfc::parse('COSC8001137NA');
        $idcif = '1904014102123';

        $this->expectException(CifDownloadException::class);
        $csfScrap->obtainFromRfcAndCif($rfc, $idcif);
    }

    public function test_send_invalid_rfc(): void
    {
        $csfScrap = $this->prepareScraper('error.html');
        $rfc = 'bad-rfc';
        $idcif = '1904014102123';

        $this->expectException(InvalidExpressionToParseException::class);
        $csfScrap->obtainFromRfcAndCif(Rfc::parse($rfc), $idcif);
    }
}
