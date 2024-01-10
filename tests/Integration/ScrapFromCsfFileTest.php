<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Integration;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PhpCfdi\CsfScraper\Exceptions\CifDownloadException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\CifFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\RfcFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Scraper;
use PhpCfdi\CsfScraper\Tests\Integration\Helpers\ScraperHelper;
use PhpCfdi\CsfScraper\Tests\TestCase;

class ScrapFromCsfFileTest extends TestCase
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
        return new ScraperHelper($client);
    }

    public function test_scrap_from_idcif_and_rfc_by_moral(): void
    {
        $csfScrap = $this->prepareScraper('scrap_moral.html');
        $expectedData = [
            'rfc' => 'DIM8701081LA',
            'id_cif' => '12345678',
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
                    'regimen_id' => '601',
                    'fecha_alta' => DateTimeImmutable::createFromFormat('!d-m-Y', '21-02-2019'),
                ],
            ],
            'extra_data' => [
                'Localidad' => '',
            ],
        ];

        $data = $csfScrap->obtainFromPdfPath('my-path')->toArray();

        $this->assertEquals($expectedData, $data);
    }

    /**
     * @requires OSFAMILY Linux
     */
    public function test_obtain_from_pdf_with_invalid_data(): void
    {
        $csfScrap = new Scraper(new Client());

        $this->expectException(CifDownloadException::class);

        $csfScrap->obtainFromPdfPath($this->filePath('csf-correct-but-invalid.pdf'));
    }

    /**
     * @requires OSFAMILY Linux
     */
    public function test_obtain_from_pdf_without_rfc(): void
    {
        $csfScrap = new Scraper(new Client());

        $this->expectException(RfcFromPdfNotFoundException::class);

        $csfScrap->obtainFromPdfPath($this->filePath('csf-without-rfc.pdf'));
    }

    /**
     * @requires OSFAMILY Linux
     */
    public function test_obtain_from_pdf_without_cif(): void
    {
        $csfScrap = new Scraper(new Client());

        $this->expectException(CifFromPdfNotFoundException::class);

        $csfScrap->obtainFromPdfPath($this->filePath('csf-without-cif.pdf'));
    }
}
