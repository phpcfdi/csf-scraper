<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\PdfReader\ShellExecException;
use RuntimeException;

/**
 * Extract the contents of a pdf file using pdftotext (apt-get install poppler-utils)
 */
final class PdfToText
{
    private string $pdftotext;

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
     * @throws ShellExecException when call to pdftotext fail
     */
    public function extract(string $path): string
    {
        $command = $this->buildCommand($path);
        $result = (new ShellExec($command))->run();
        if (0 !== $result->exitStatus()) {
            throw new ShellExecException(
                "Running pdftotext exit with error (exit status: {$result->exitStatus()})",
                $command,
                $result
            );
        }
        return $result->output();
    }

    /**
     * @return string[]
     */
    public function buildCommand(string $pdfFile): array
    {
        return [$this->pdftotext, '-eol', 'unix', '-raw', '-q', $pdfFile, '-'];
    }
}
