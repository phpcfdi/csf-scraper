<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\PdfReader;

use PhpCfdi\CsfScraper\Exceptions\PdfReader\ShellExecException;

/**
 * Extract the contents of a pdf file using pdftotext (apt-get install poppler-utils)
 */
final class PdfToText
{
    private string $pdftotext;

    /**
     * @throws ShellExecException when pdftotext command path was not found
     */
    public function __construct(string $pathPdfToText = '')
    {
        if ('' === $pathPdfToText) {
            $pathPdfToText = $this->discoverPathToPdfToText();
        }
        $this->pdftotext = $pathPdfToText;
    }

    /**
     * @throws ShellExecException when pdftotext command path was not found
     */
    private function discoverPathToPdfToText(): string
    {
        $command = ['which', 'pdftotext'];
        $result = (new ShellExec($command))->run();
        $pathPdfToText = trim($result->output());
        if (0 !== $result->exitStatus() || '' === $pathPdfToText) {
            throw new ShellExecException('pdftotext command path was not found', $command, $result);
        }

        return $pathPdfToText;
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
