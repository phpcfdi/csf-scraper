<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\CifFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\RfcFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Interfaces\ScraperInterface;
use PhpCfdi\CsfScraper\PdfReader\CsfExtractor;
use PhpCfdi\CsfScraper\PdfReader\PdfToText;
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
     * @throws RuntimeException
     */
    public function obtainFromRfcAndCif(Rfc $rfc, string $idCIF): PersonaMoral|PersonaFisica
    {
        $uri = sprintf(self::$url, $idCIF, $rfc->getRfc());
        try {
            $html = $this->obtainHtml($uri);
            return (new DataExtractor($html))->extract($rfc->isFisica());
        } catch (GuzzleException $exception) {
            throw new \RuntimeException('The request has failed', previous: $exception);
        }
    }

    public function obtainFromPdfPath(string $path): PersonaMoral|PersonaFisica
    {
        $contents = $this->pdfToTextContent($path);

        $csfExtractor = new CsfExtractor($contents);
        $rfc = $csfExtractor->getRfc();
        $cif = $csfExtractor->getCifId();
        if (null === $rfc) {
            throw new RfcFromPdfNotFoundException('Cannot obtain rfc from given PDF');
        }
        if (null === $cif) {
            throw new CifFromPdfNotFoundException('Cannot obtain cif from given PDF');
        }
        return $this->obtainFromRfcAndCif(Rfc::parse($rfc), $cif);
    }

    /**
     * @param string $path
     * @return string
     * @throws \PhpCfdi\CsfScraper\Exceptions\PdfReader\ShellExecException when call to pdftotext fail
     */
    protected function pdfToTextContent(string $path): string
    {
        $pdfToText = new PdfToText();
        return $pdfToText->extract($path);
    }

    protected function obtainHtml(string $uri): string
    {
        $request = $this->client->request('GET', $uri);
        return (string) $request->getBody();
    }
}
