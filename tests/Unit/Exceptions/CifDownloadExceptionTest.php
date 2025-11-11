<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Exceptions;

use PhpCfdi\CsfScraper\Exceptions\CifDownloadException;
use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\Tests\TestCase;
use Throwable;

final class CifDownloadExceptionTest extends TestCase
{
    public function test_implements_library_exception_type(): void
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        $this->assertTrue(is_a(CifDownloadException::class, CsfScraperException::class, true));
    }

    public function test_properties(): void
    {
        $url = 'https://example.com';
        $previous = $this->createMock(Throwable::class);
        $exception = new CifDownloadException($url, $previous);
        $this->assertSame($url, $exception->getUrl());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
