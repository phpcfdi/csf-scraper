<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Exceptions\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use RuntimeException;

final class PdfToTextConvertException extends RuntimeException implements CsfScraperException
{
    /**
     * @param string[] $command
     */
    public function __construct(
        string $message,
        private array $command,
        private int $exitCode,
        private string $output,
        private string $error,
    ) {
        parent::__construct($message);
    }

    /** @return string[] */
    public function getCommand(): array
    {
        return $this->command;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function getGetOutput(): string
    {
        return $this->output;
    }

    public function getError(): string
    {
        return $this->error;
    }

}