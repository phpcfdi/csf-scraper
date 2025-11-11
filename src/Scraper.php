<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\CifFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Exceptions\PdfReader\RfcFromPdfNotFoundException;
use PhpCfdi\CsfScraper\Interfaces\ScraperInterface;
use PhpCfdi\CsfScraper\PdfReader\CsfExtractor;
use PhpCfdi\CsfScraper\PdfReader\PdfToText;
use PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException;
use PhpCfdi\Rfc\Rfc;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Main class to obtain the data from a "Persona Moral" or a "Persona Física" from SAT website
 */
class Scraper implements ScraperInterface
{
    public static string $url = 'https://siat.sat.gob.mx/app/qr/faces/pages/mobile/validadorqr.jsf?D1=10&D2=1&D3=%s_%s';

    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * Factory method to create a scraper object with configuration that simply works
     */
    public static function create(): self
    {
        return new self(new Client([
            'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
        ]));
    }

    /**
     * Obtain a PersonaMoral or PersonaFisica object with the information from SAT website
     *
     * @throws Exceptions\CifDownloadException
     * @throws Exceptions\CifNotFoundException
     */
    public function obtainFromRfcAndCif(Rfc $rfc, string $idCIF): PersonaMoral|PersonaFisica
    {
        $uri = sprintf(static::$url, $idCIF, $rfc->getRfc());
        $html = $this->obtainHtml($uri);
        $person = (new DataExtractor($html))->extract($rfc->isFisica());
        $person->setRfc($rfc->getRfc());
        $person->setIdCif($idCIF);
        return $person;
    }

    /**
     * Obtain a PersonaMoral or PersonaFisica object with the information from SAT website
     * The RFC and CIF is taken from the "Constancia de Situación Fiscal" PDF file
     *
     * @throws Exceptions\CifNotFoundException
     * @throws CifFromPdfNotFoundException
     * @throws Exceptions\PdfReader\EmptyPdfContentException
     * @throws RfcFromPdfNotFoundException
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
     * Helper method to extract the text information from PDF to plain text
     *
     * @throws Exceptions\PdfReader\PdfToTextConvertException when call to pdftotext fail
     */
    protected function pdfToTextContent(string $path): string
    {
        $pdfToText = new PdfToText();
        return $pdfToText->extract($path);
    }

    /**
     * Helper method to download the webpage that contains all the "Persona" information from SAT website
     *
     * @throws Exceptions\CifDownloadException
     */
    protected function obtainHtml(string $uri): string
    {
        try {
            $request = $this->client->request('GET', $uri);
        } catch (ClientExceptionInterface $exception) {
            throw new Exceptions\CifDownloadException($uri, $exception);
        }
        return (string) $request->getBody();
    }
}
