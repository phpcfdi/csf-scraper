<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use PhpCfdi\CsfScraper\Exceptions\CifNotFoundException;
use PhpCfdi\CsfScraper\Interfaces\DataExtractorInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

final class DataExtractor implements DataExtractorInterface
{
    public function __construct(private string $html)
    {
    }

    /**
     * @throws CifNotFoundException
     */
    public function extract2(bool $isFisica): PersonaMoral|PersonaFisica
    {
        $html = $this->clearHtml($this->html);
        $person = $isFisica ? new PersonaFisica() : new PersonaMoral();

        $crawler = new Crawler($html);
        $errorElement = $crawler->filter('span[class=ui-messages-info-detail]');
        if (1 === $errorElement->count()) {
            throw new CifNotFoundException('Failed to found CIF info.');
        }
        $elements = $crawler->filter('td[role="gridcell"]');

        $elements->each(function (Crawler $elem, int $index) use ($person): void {
            if ($index >= 40) {
                return;
            }
            if (0 === $elem->filter('span')->count()) {
                $property = $person->getKeyNameByIndex($index);
                if (null !== $property) {
                    $person->{$property} = trim($elem->text());
                }
            }
        });
        $regimenes = $this->getRegimenes($crawler);
        if ([] !== $regimenes) {
            $person->addRegimenes(...$regimenes);
        }
        return $person;
    }

    /**
     * @throws CifNotFoundException
     */
    public function extract(bool $isFisica): PersonaMoral|PersonaFisica
    {
        $html = $this->clearHtml($this->html);
        $person = $isFisica ? new PersonaFisica() : new PersonaMoral();

        $crawler = new Crawler($html);
        $errorElement = $crawler->filter('span[class=ui-messages-info-detail]');
        if (1 === $errorElement->count()) {
            throw new CifNotFoundException('Failed to found CIF info.');
        }

        $titles = $person->getTdTitlesTextToSearch();
        foreach ($titles as $property => $text) {
            $person->{$property} = $this->getValueByTdTitleText($crawler, $text);
        }
        $regimenes = $this->getRegimenes($crawler);
        if ([] !== $regimenes) {
            $person->addRegimenes(...$regimenes);
        }

        return $person;
    }

    private function getValueByTdTitleText(Crawler $crawler, string $valueToSearch): string
    {
        $element = $crawler->filterXPath("//td[@role='gridcell']/span[contains(normalize-space(.), '{$valueToSearch}')]");
        $node = $element->getNode(0);
        if (null === $node) {
            return '';
        }
        return trim(str_replace($valueToSearch, ' ', $this->normalizeWhiteSpaces($node->parentNode?->parentNode?->textContent ?? '')));
    }

    private function normalizeWhiteSpaces(string $str): string
    {
        $str = trim($str);
        $str = str_replace("\r", "\n", $str);
        $str = preg_replace(['/\n+/', '/[ \t]+/'], [' ', ' '], $str);
        return $str ?? '';
    }

    private function clearHtml(string $html): string
    {
        return str_replace('<?xml version="1.0" encoding="UTF-8" ?>', '', $html);
    }

    /**
     *
     * @return Regimen[]
     * @throws RuntimeException
     */
    private function getRegimenes(Crawler $crawler): array
    {
        $tbodies = $crawler->filter('tbody[class="ui-datatable-data ui-widget-content"]');
        $valuesCount = 0;
        $regimenes = [];
        $tbodies->each(function (Crawler $elem, int $index) use (&$valuesCount, &$regimenes): void {
            if (4 === $index) {
                $elements = $elem->filter('td[role="gridcell"]');
                $elements->each(function (Crawler $childElem) use (&$valuesCount, &$regimenes): void {
                    if (0 === $childElem->filter('span')->count()) {
                        $value = trim($childElem->text());
                        $localIndex = (int) ($valuesCount / 2);
                        if (0 === $valuesCount % 2) {
                            $regimenes[$localIndex] = new Regimen();
                            $regimenes[$localIndex]->setRegimen($value);
                        } else {
                            $regimenes[$localIndex]->setFechaAlta($value);
                        }
                        $valuesCount++;
                    }
                });
            }
        });
        return $regimenes;
    }
}
