<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

use RuntimeException;

/**
 *
 * @internal
 */
final class CsfExtractor
{
    /**
     * @var string[]
     */
    private array $lines;

    public function __construct(string $contents)
    {
        if ('' == $contents) {
            throw new RuntimeException('Cannot read pdf contents.');
        }
        $this->lines = explode("\n", $contents);
    }

    public function getRfc(): ?string
    {
        $matchesIndex = $this->getMatchesIndex('RFC');
        return $this->obtainCleanValue($matchesIndex);
    }

    public function getCifId(): ?string
    {
        $matchesIndex = $this->getMatchesIndex('idCIF');
        return $this->obtainCleanValue($matchesIndex);
    }

    /**
     *
     * @param string[] $matchesIndex
     */
    private function obtainCleanValue(array $matchesIndex): ?string
    {
        if (0 !== count($matchesIndex)) {
            $values = explode(':', array_pop($matchesIndex));
            if (isset($values[1])) {
                return trim($values[1]);
            }
        }
        return null;
    }

    /**
     *
     * @return string[]
     */
    private function getMatchesIndex(string $searchable): array
    {
        return array_filter($this->lines, function ($haystack) use ($searchable) {
            $pos = strpos($haystack, $searchable);
            return is_int($pos);
        });
    }
}
