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
            ['Opcional para Grupos de Sociedades', '623'],
            ['Coordinados', '624'],
            ['Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas', '625'],
            ['Régimen Simplificado de Confianza', '626'],
        ];
    }

    /**
     * @dataProvider regimenesProvider
     */
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
}
