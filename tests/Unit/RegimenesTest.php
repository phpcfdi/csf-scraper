<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit;

use PhpCfdi\CsfScraper\Regimen;
use PhpCfdi\CsfScraper\Regimenes;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class RegimenesTest extends TestCase
{
    public function test_add_regimenes(): void
    {
        $first = new Regimen();
        $first->setRegimen('Sin obligaciones fiscales');
        $second = new Regimen();
        $second->setRegimen('Demás ingresos');

        $regimenes = new Regimenes();
        $regimenes->addRegimen($first, $second);

        $this->assertSame([$first, $second], $regimenes->getRegimenes());
    }
}
