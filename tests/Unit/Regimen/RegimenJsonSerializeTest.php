<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Regimen;

use PhpCfdi\CsfScraper\Regimen;
use PhpCfdi\CsfScraper\Tests\TestCase;

class RegimenJsonSerializeTest extends TestCase
{
    public function test_serialize_default_regimen(): void
    {
        $regimen = new Regimen();

        $json = (string) json_encode($regimen);

        $this->assertSame([
            'regimen' => '',
            'regimen_id' => '',
            'fecha_alta' => null,
        ], json_decode($json, associative: true));
    }
}
