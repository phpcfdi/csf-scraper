<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PhpCfdi\CsfScraper\Interfaces\ScraperInterface;
use PhpCfdi\Rfc\Rfc;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

class Scraper implements ScraperInterface
{
    private ClientInterface $client;
    public static string $url = 'https://siat.sat.gob.mx/app/qr/faces/pages/mobile/validadorqr.jsf?D1=10&D2=1&D3=%s_%s';

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     *
     * @return array<int|string, array<int|string, mixed>|string>
     *
     * @throws RuntimeException
     */
    public function data(string|Rfc $rfc, string $idCIF): array
    {
        $rfc = $this->parseRfc($rfc);
        $isFisica = $rfc->isFisica();
        try {
            $uri = urlencode(sprintf(self::$url, $idCIF, $rfc->getRfc()));
            $html = $this->client->request('GET', $uri)
                ->getBody()
                ->getContents();
            return $this->extractData($html, $isFisica);
        } catch (GuzzleException $exception) {
            throw new \RuntimeException('The request has failed', 0, $exception);
        }
    }

    private function parseRfc(string|Rfc $rfc): Rfc
    {
        if (is_string($rfc)) {
            $rfc = Rfc::parse($rfc);
        }
        return $rfc;
    }

    /**
     *
     * @return array<int|string, array<int|string, mixed>|string>
     * @throws RuntimeException
     */
    private function extractData(string $html, bool $isFisica): array
    {
        $html = $this->clearHtml($html);
        $getKeyNameByIndex = $isFisica ? 'getKeyNameByIndexFisica' : 'getKeyNameByIndexMoral';

        $crawler = new Crawler($html);
        $elements = $crawler->filter('td[role="gridcell"]');
        $values = [];

        $elements->each(function (Crawler $elem, int $index) use (&$values, $getKeyNameByIndex): void {
            if ($index >= 40) {
                return;
            }
            if (0 === $elem->filter('span')->count()) {
                $keyName = $this->$getKeyNameByIndex($index);

                if (null !== $keyName) {
                    $values[$keyName] = trim($elem->text());
                }
            }
        });
        $regimenes = $this->getRegimenes($crawler);
        if (! empty($regimenes)) {
            $values['regimenes'] = $regimenes;
        }
        return $values;
    }

    private function clearHtml(string $html): string
    {
        $html = str_replace('<?xml version="1.0" encoding="UTF-8" ?>', '', $html);
        return str_replace('<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />', '', $html);
    }

    /**
     *
     * @return array<int|string, string|mixed>
     * @throws RuntimeException
     */
    private function getRegimenes(Crawler $crawler): array
    {
        $tbodies = $crawler->filter('tbody[class="ui-datatable-data ui-widget-content"]');
        $valuesCount = 0;
        /** @var array<string, string> */
        $regimenes = [];
        $tbodies->each(function (Crawler $elem, int $index) use (&$valuesCount, &$regimenes): void {
            if (4 === $index) {
                $elements = $elem->filter('td[role="gridcell"]');
                $elements->each(function (Crawler $childElem) use (&$valuesCount, &$regimenes): void {
                    if (0 === $childElem->filter('span')->count()) {
                        $value = trim($childElem->text());
                        $count = $valuesCount;
                        $localIndex = (int) ($count / 2);
                        $localKey = 0 === $count % 2 ? 'regimen' : 'fecha_alta';
                        $regimenes[$localIndex][$localKey] = $value;
                        $valuesCount++;
                    }
                });
            }
        });
        return $regimenes;
    }

    private function getKeyNameByIndexMoral(int $index): ?string
    {
        return match ($index) {
            2 => 'razon_social',
            4 => 'regimen_de_capital',
            6 => 'fecha_constitucion',
            8 => 'fecha_inicio_operaciones',
            10 => 'situacion_contribuyente',
            12 => 'fecha_ultimo_cambio_situacion',
            17 => 'entidad_federativa',
            19 => 'municipio_delegacion',
            21 => 'colonia',
            23 => 'tipo_vialidad',
            25 => 'nombre_vialidad',
            27 => 'numero_exterior',
            29 => 'numero_interior',
            31 => 'codigo_postal',
            33 => 'correo_electronico',
            35 => 'al',
            default => null
        };
    }

    private function getKeyNameByIndexFisica(int $index): ?string
    {
        return match ($index) {
            2 => 'curp',
            4 => 'nombre',
            6 => 'apellido_paterno',
            8 => 'apellido_materno',
            10 => 'fecha_nacimiento',
            12 => 'fecha_inicio_operaciones',
            14 => 'situacion_contribuyente',
            16 => 'fecha_ultimo_cambio_situacion',
            21 => 'entidad_federativa',
            23 => 'municipio_delegacion',
            25 => 'colonia',
            27 => 'tipo_vialidad',
            29 => 'nombre_vialidad',
            31 => 'numero_exterior',
            33 => 'numero_interior',
            35 => 'codigo_postal',
            37 => 'correo_electronico',
            39 => 'al',
            default => null
        };
    }
}
