<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use PhpCfdi\CsfScraper\Exceptions\CifNotFoundException;
use PhpCfdi\CsfScraper\Interfaces\DataExtractorInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

final class DataExtractor implements DataExtractorInterface
{
    private string $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function extract(bool $isFisica): PersonaMoral|PersonaFisica
    {
        $html = $this->clearHtml($this->html);
        $person = $isFisica ? new PersonaFisica() : new PersonaMoral();

        $crawler = new Crawler($html);
        $errorElement = $crawler->filter('span[class=ui-messages-info-detail]');
        if (1 === $errorElement->count()) {
            throw new CifNotFoundException('Failed to found CIF info.', 500);
        }
        $elements = $crawler->filter('td[role="gridcell"]');

        $elements->each(function (Crawler $elem, int $index) use ($person): void {
            if ($index >= 40) {
                return;
            }
            if (0 === $elem->filter('span')->count()) {
                $property = $person->getKeyNameByIndex($index);
                if (null !== $property) {
                    $person->$property = trim($elem->text());
                }
            }
        });
        $regimenes = $this->getRegimenes($crawler);
        if (count($regimenes) > 0) {
            $person->addRegimenes(...$regimenes);
        }
        return $person;
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
        /** @var Regimen[]*/
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
