<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Exceptions\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\PdfReader\ShellExecResult;
use RuntimeException;

final class ShellExecException extends RuntimeException implements CsfScraperException
{
    /**
     * @param string[] $command
     */
    public function __construct(string $message, private array $command, private ShellExecResult $result)
    {
        parent::__construct($message);
    }

    /** @return string[] */
    public function getCommand(): array
    {
        return $this->command;
    }

    public function getResult(): ShellExecResult
    {
        return $this->result;
    }
}
