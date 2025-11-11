<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Exceptions;

use PhpCfdi\CsfScraper\Exceptions\CifNotFoundException;
use PhpCfdi\CsfScraper\Exceptions\CsfScraperException;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class CifNotFoundExceptionTest extends TestCase
{
    public function test_implements_library_exception_type(): void
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        $this->assertTrue(is_a(CifNotFoundException::class, CsfScraperException::class, true));
    }
}
