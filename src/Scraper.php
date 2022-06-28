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
use PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException;
use PhpCfdi\Rfc\Rfc;

class Scraper implements ScraperInterface
{
    private ClientInterface $client;
    public static string $url = 'https://siat.sat.gob.mx/app/qr/faces/pages/mobile/validadorqr.jsf?D1=10&D2=1&D3=%s_%s';

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exceptions\CifDownloadException
     * @throws Exceptions\CifNotFoundException
     */
    public function obtainFromRfcAndCif(Rfc $rfc, string $idCIF): PersonaMoral|PersonaFisica
    {
        $uri = sprintf(self::$url, $idCIF, $rfc->getRfc());
        $html = $this->obtainHtml($uri);
        $person = (new DataExtractor($html))->extract($rfc->isFisica());
        $person->setRfc($rfc->getRfc());
        $person->setIdCif($idCIF);
        return $person;
    }

    /**
     * @param string $path
     * @return PersonaMoral|PersonaFisica
     * @throws Exceptions\CifNotFoundException
     * @throws Exceptions\PdfReader\CifFromPdfNotFoundException
     * @throws Exceptions\PdfReader\EmptyPdfContentException
     * @throws Exceptions\PdfReader\RfcFromPdfNotFoundException
     * @throws InvalidExpressionToParseException
     */
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
     * @throws Exceptions\PdfReader\ShellExecException when call to pdftotext fail
     */
    protected function pdfToTextContent(string $path): string
    {
        $pdfToText = new PdfToText();
        return $pdfToText->extract($path);
    }

    /**
     * @throws Exceptions\CifDownloadException
     */
    protected function obtainHtml(string $uri): string
    {
        try {
            $request = $this->client->request('GET', $uri);
        } catch (GuzzleException $exception) {
            throw new Exceptions\CifDownloadException($uri, $exception);
        }
        return (string) $request->getBody();
    }
}
