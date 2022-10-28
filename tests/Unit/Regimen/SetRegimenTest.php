<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Regimen;

use PhpCfdi\CsfScraper\Regimen;
use PhpCfdi\CsfScraper\Tests\TestCase;

class SetRegimenTest extends TestCase
{
    /**
     *
     * @return array<int, array<int, string>>
     */
    public function regimenesProvider(): array
    {
        return [
            ['General de Ley Personas Morales', '601'],
            ['Personas Morales con Fines no Lucrativos', '603'],
            ['Sueldos y Salarios e Ingresos Asimilados a Salarios', '605'],
            ['Arrendamiento', '606'],
            ['Régimen de Enajenación o Adquisición de Bienes', '607'],
            ['Demás ingresos', '608'],
            ['Residentes en el Extranjero sin Establecimiento Permanente en México', '610'],
            ['Ingresos por Dividendos (socios y accionistas)', '611'],
            ['Personas Físicas con Actividades Empresariales y Profesionales', '612'],
            ['Ingresos por intereses', '614'],
            ['Régimen de los ingresos por obtención de premios', '615'],
            ['Sin obligaciones fiscales', '616'],
            ['Sociedades Cooperativas de Producción que optan por diferir sus ingresos', '620'],
            ['Incorporación Fiscal', '621'],
            ['Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras', '622'],
            ['Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras PM', '622'],
            ['Opcional para Grupos de Sociedades', '623'],
            ['Coordinados', '624'],
            ['Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas', '625'],
            ['Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas.', '625'],
            ['Régimen Simplificado de Confianza', '626'],
        ];
    }

    /** @dataProvider regimenesProvider */
    public function test_check_regimenes_are_well_assigned(string $regimenText, string $regimenIdExpected): void
    {
        $regimen = new Regimen();
        $regimen->setRegimen($regimenText);

        $this->assertSame($regimenIdExpected, $regimen->getRegimenId());
    }

    public function test_detect_correct_regimen_case_insensitive(): void
    {
        $regimen = new Regimen();
        $regimen->setRegimen('DEMÁS INGRESOS');

        $this->assertSame('608', $regimen->getRegimenId());
    }

    public function test_returns_empty_when_not_found(): void
    {
        $regimen = new Regimen();
        $regimen->setRegimen('regimen no incluido');

        $this->assertSame('', $regimen->getRegimenId());
    }

    public function test_fecha_alta_with_valid_value(): void
    {
        $input = '01-02-2001';
        $regimen = new Regimen();
        $regimen->setFechaAlta($input);
        $fechaAlta = $regimen->getFechaAlta();
        if (null === $fechaAlta) {
            $this->fail("Fecha alta $input was not interpreted");
        }
        $this->assertSame(1, (int) $fechaAlta->format('d'));
        $this->assertSame(2, (int) $fechaAlta->format('m'));
        $this->assertSame(2001, (int) $fechaAlta->format('Y'));
        $this->assertSame(0, (int) $fechaAlta->format('H'));
        $this->assertSame(0, (int) $fechaAlta->format('i'));
        $this->assertSame(0, (int) $fechaAlta->format('s'));
    }

    public function test_fecha_alta_with_empty_value(): void
    {
        $input = '';
        $regimen = new Regimen();
        $regimen->setFechaAlta($input);
        $this->assertNull($regimen->getFechaAlta());
    }

    /** @return array<string, array{string}> */
    public function provider_fecha_alta_with_invalid_value(): array
    {
        return [
            'empty' => [''],
            'incorrect format' => ['2022-12-31'],
            'incomplete' => ['1-1'],
        ];
    }

    /** @dataProvider  provider_fecha_alta_with_invalid_value */
    public function test_fecha_alta_with_invalid_value(string $input): void
    {
        $regimen = new Regimen();
        $regimen->setFechaAlta($input);
        $this->assertNull($regimen->getFechaAlta());
    }
}
