<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Regimen;

use PhpCfdi\CsfScraper\Regimen;
use PhpCfdi\CsfScraper\Regimenes;
use PhpCfdi\CsfScraper\Tests\TestCase;

class RegimenesJsonSerializeTest extends TestCase
{
    public function test_serialize_default_regimenes(): void
    {
        $regimenes = new Regimenes();

        $regimenes->addRegimen(new Regimen(), new Regimen());

        $json = (string) json_encode($regimenes);

        $this->assertSame([
            [
                'regimen' => '',
                'regimen_id' => '',
                'fecha_alta' => null,
            ],
            [
                'regimen' => '',
                'regimen_id' => '',
                'fecha_alta' => null,
            ],
        ], json_decode($json, associative: true));
    }
}
