<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\PdfReader\EmptyPdfContentException;

/** @internal */
final readonly class CsfExtractor
{
    /** @var list<string> */
    private array $lines;

    /**
     * @throws EmptyPdfContentException
     */
    public function __construct(string $contents)
    {
        if ('' === $contents) {
            throw new EmptyPdfContentException('Cannot read pdf contents.');
        }
        $this->lines = explode("\n", $contents);
    }

    public function getRfc(): ?string
    {
        $matchesText = $this->obtainFirstLineStartWith('RFC:');
        return $this->obtainCleanValue($matchesText);
    }

    public function getCifId(): ?string
    {
        $matchesText = $this->obtainFirstLineStartWith('idCIF:');
        return $this->obtainCleanValue($matchesText);
    }

    private function obtainFirstLineStartWith(string $searchable): string
    {
        foreach ($this->lines as $line) {
            if (str_starts_with($line, $searchable)) {
                return $line;
            }
        }

        return '';
    }

    private function obtainCleanValue(string $entry): ?string
    {
        $values = explode(':', $entry);
        if (! isset($values[1])) {
            return null;
        }

        return trim($values[1]);
    }
}
