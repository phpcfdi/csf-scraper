<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Exceptions;

use RuntimeException;
use Throwable;

final class CifDownloadException extends RuntimeException implements CsfScraperException
{
    public function __construct(private readonly string $url, Throwable $previous)
    {
        parent::__construct('The request has failed', previous: $previous);
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
