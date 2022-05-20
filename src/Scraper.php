<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PhpCfdi\CsfScraper\Interfaces\ScraperInterface;
use PhpCfdi\Rfc\Rfc;
use RuntimeException;

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
        try {
            $uri = urlencode(sprintf(self::$url, $idCIF, $rfc->getRfc()));
            $html = (string) $this->client->request('GET', $uri)->getBody();
            return (new DataExtractor($html))->extract($rfc->isFisica());
        } catch (GuzzleException $exception) {
            throw new \RuntimeException('The request has failed', 0, $exception);
        }
    }

    /**
     * @throws \PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException
     */
    private function parseRfc(string|Rfc $rfc): Rfc
    {
        if (is_string($rfc)) {
            $rfc = Rfc::parse($rfc);
        }
        return $rfc;
    }
}
