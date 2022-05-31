<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

/**
 * Contains the result of ShellExec::exec()
 *
 * @see ShellExec
 *
 * NOTE: Changes will not be considering a bracking compatibility change since this utility is for internal usage only
 * @internal
 */
final class ShellExecResult
{
    public function __construct(
        private readonly string $commandLine,
        private readonly int $exitStatus,
        private readonly string $output,
        private readonly string $errors
    ) {
    }

    public function commandLine(): string
    {
        return $this->commandLine;
    }

    public function exitStatus(): int
    {
        return $this->exitStatus;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function errors(): string
    {
        return $this->errors;
    }
}
