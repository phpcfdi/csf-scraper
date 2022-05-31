<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

use RuntimeException;

/**
 * Extract the contents of a pdf file using pdftotext (apt-get install poppler-utils)
 */
final class PdfToText
{
    private readonly string $pdftotext;

    public function __construct(string $pathPdfToText = '')
    {
        if ('' === $pathPdfToText) {
            $pathPdfToText = trim(strval(shell_exec('which pdftotext')));
            if ('' === $pathPdfToText) {
                throw new RuntimeException('pdftotext command was not found');
            }
        }
        $this->pdftotext = $pathPdfToText;
    }

    /**
     * @return string file contents
     */
    public function extract(string $path): string
    {
        $shellExec = (new ShellExec($this->buildCommand($path)))->run();
        if (0 !== $shellExec->exitStatus()) {
            throw new RuntimeException("Running pdftotext exit with error (exit status: {$shellExec->exitStatus()})");
        }
        return $shellExec->output();
    }

    /**
     * @return string[]
     */
    public function buildCommand(string $pdfFile): array
    {
        return [$this->pdftotext, '-eol', 'unix', '-raw', '-q', $pdfFile, '-'];
    }
}
