<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Integration;

use PhpCfdi\CsfScraper\Tests\TestCase;
use Symfony\Component\Process\Process;

final class CliTest extends TestCase
{
    /**
     * @param string[] $arguments
     */
    private function runCli(array $arguments): Process
    {
        $command = [PHP_BINARY, __DIR__ . '/../../bin/csf-scraper', ...$arguments];
        $process = new Process($command);
        $process->run();
        return $process;
    }

    public function test_command_help(): void
    {
        $process = $this->runCli(['help']);

        $this->assertTrue($process->isSuccessful());
        $this->assertStringContainsString('Uso:', $process->getOutput());
        $this->assertStringContainsString('obtain <id-cif> <rfc>', $process->getOutput());
    }

    public function test_command_help_legacy(): void
    {
        $process = $this->runCli(['--help']);

        $this->assertTrue($process->isSuccessful());
        $this->assertStringContainsString('Uso:', $process->getOutput());
    }

    public function test_command_help_in_any_position(): void
    {
        $process = $this->runCli(['obtain', '--help']);

        $this->assertTrue($process->isSuccessful());
        $this->assertStringContainsString('Uso:', $process->getOutput());
    }

    public function test_obtain_with_timeout(): void
    {
        $process = $this->runCli(['obtain', '12345678', 'RFC010101AAA', '--timeout', '5']);

        // We expect it to fail because the RFC/CIF are fake, but it should NOT fail because of the option
        $this->assertFalse($process->isSuccessful());
        $this->assertStringContainsString('Error:', $process->getErrorOutput());
        $this->assertStringNotContainsString('no reconocido', $process->getErrorOutput());
    }

    public function test_command_schema(): void
    {
        $process = $this->runCli(['schema']);

        $this->assertTrue($process->isSuccessful());
        $this->assertJson($process->getOutput());
        $this->assertStringContainsString('csf.schema.json', $process->getOutput());
    }

    public function test_obtain_missing_arguments(): void
    {
        $process = $this->runCli(['obtain']);

        $this->assertFalse($process->isSuccessful());
        $this->assertStringContainsString('Error: El comando obtain requiere [id-cif rfc] o [archivo|url|-]', $process->getErrorOutput());
    }

    public function test_obtain_local_file(): void
    {
        $path = $this->filePath('csf-without-cif.pdf');
        $process = $this->runCli(['obtain', $path]);

        // Expect failure because it doesn't have CIF ID, but it confirms the file was read and processed
        $this->assertFalse($process->isSuccessful());
        $this->assertStringContainsString('Cannot obtain cif from given PDF', $process->getErrorOutput());
    }
}
