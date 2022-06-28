<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

/**
 * Contains the result of ShellExec::exec()
 *
 * @see ShellExec
 *
 * NOTE: Changes will not be considering a breaking compatibility change since this utility is for internal usage only
 * @internal
 */
final class ShellExecResult
{
    public function __construct(
        private string $commandLine,
        private int $exitStatus,
        private string $output,
        private string $errors
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
