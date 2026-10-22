<?php

declare(strict_types=1);

use PhpCfdi\CsfScraper\Scraper;

require_once __DIR__ . '/../../vendor/autoload.php';

exit(call_user_func(new class (...($argv ?? [__FILE__])) {
    public bool $debug;

    public string $command;

    /** @var list<string> */
    public array $arguments;

    public function __construct(string $command, string ...$arguments)
    {
        $this->debug = false;
        $this->command = $command;
        foreach ($arguments as $key => $value) {
            if (in_array($value, ['-d', '--debug'])) {
                $this->debug = true;
                unset($arguments[$key]);
            }
        }
        $this->arguments = array_values($arguments);
    }

    public function printHelp(): void
    {
        $command = basename($this->command);
        echo implode(PHP_EOL, [
            "$command - Obtiene la información con base en un archivo PDF",
            'Sintaxis:',
            "    $command help|-h|--help",
            "    $command [-d|--debug] CSF-FILE",
            'Argumentos:',
            '    --debug|-d  Si se encuentra un error, muestra la excepción y todas las excepciones previas.',
            '    CSF-FILE    Archivo PDF con la constancia de situación fiscal.',
            'Salida:',
            '    Información extraída del sitio del SAT en formato JSON',
            'Licencia y autoría:',
            '    La misma del proyecto https://github.com/phpcfdi/csf-scraper',
            'Advertencia:',
            '    Este es un ejemplo de uso y este script pruebe cambiar en cualquier momento.',
            '    Use su propia implementación para evitar conflictos entre versiones.',
            '',
            '',
        ]);
    }

    public function __invoke(): int
    {
        if ([] !== array_intersect($this->arguments, ['--help', '-h', 'help'])) {
            $this->printHelp();
            return 0;
        }
        try {
            $this->execute();
            return 0;
        } catch (Throwable $exception) {
            if ($this->debug) {
                for ($current = $exception; null !== $current; $current = $current->getPrevious()) {
                    $this->printError(sprintf('Exception (%s) %s', $exception::class, $exception->getMessage()));
                    $this->printError(sprintf('At %s(%s)', $exception->getFile(), $exception->getLine()));
                    $this->printError($exception->gettraceAsString());
                }
            } else {
                $this->printError(sprintf('ERROR: %s', $exception->getMessage()));
            }
            return is_int($exception->getCode()) && $exception->getCode() > 0 ? $exception->getCode() : 1;
        }
    }

    private function printError(string ...$messages): void
    {
        foreach ($messages as $message) {
            file_put_contents('php://stderr', $message, FILE_APPEND);
            file_put_contents('php://stderr', PHP_EOL, FILE_APPEND);
        }
    }

    public function execute(): void
    {
        $filename = '';

        $arguments = $this->arguments;
        foreach ($arguments as $argument) {
            if ('' === $filename) {
                $filename = $argument;
            } else {
                throw new RuntimeException(sprintf('Unexpected argument "%s".', $argument));
            }
        }
        if ('' === $filename) {
            throw new RuntimeException('Expected argument CSF-FILE is not set.');
        }
        if (! file_exists($filename)) {
            throw new RuntimeException(sprintf('File "%s" does not exists.', $filename));
        }

        $scraper = Scraper::create();
        $person = $scraper->obtainFromPdfPath($filename);
        echo json_encode($person, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), PHP_EOL;
    }
}));
