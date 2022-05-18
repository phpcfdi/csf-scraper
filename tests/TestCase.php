<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function filePath(string $filename): string
    {
        return __DIR__ . '/_files/' . $filename;
    }

    public static function fileContents(string $filename): string
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        return strval(@file_get_contents(static::filePath($filename))) ?: '';
    }
}
