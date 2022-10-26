<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper;

use DateTimeImmutable;
use JsonSerializable;

class Regimen implements JsonSerializable
{
    private string $regimen = '';
    private string $regimenId = '';
    private ?DateTimeImmutable $fechaAlta = null;
    /**
     *
     * @var array<int, string[]>
     */
    private array $regimenes = [
        [
            'id' => '601',
            'texto' => 'General de Ley Personas Morales',
        ],
        [
            'id' => '603',
            'texto' => 'Personas Morales con Fines no Lucrativos',
        ],
        [
            'id' => '605',
            'texto' => 'Sueldos y Salarios e Ingresos Asimilados a Salarios',
        ],
        [
            'id' => '606',
            'texto' => 'Arrendamiento',
        ],
        [
            'id' => '607',
            'texto' => 'Enajenación o Adquisición de Bienes',
        ],
        [
            'id' => '608',
            'texto' => 'Demás ingresos',
        ],
        [
            'id' => '610',
            'texto' => 'Residentes en el Extranjero sin Establecimiento Permanente en México',
        ],
        [
            'id' => '611',
            'texto' => 'Ingresos por Dividendos (socios y accionistas)',
        ],
        [
            'id' => '612',
            'texto' => 'Personas Físicas con Actividades Empresariales y Profesionales',
        ],
        [
            'id' => '614',
            'texto' => 'Ingresos por intereses',
        ],
        [
            'id' => '615',
            'texto' => 'ingresos por obtención de premios',
        ],
        [
            'id' => '616',
            'texto' => 'Sin obligaciones fiscales',
        ],
        [
            'id' => '620',
            'texto' => 'Sociedades Cooperativas de Producción que optan por diferir sus ingresos',
        ],
        [
            'id' => '621',
            'texto' => 'Incorporación Fiscal',
        ],
        [
            'id' => '622',
            'texto' => 'Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras',
        ],
        [
            'id' => '623',
            'texto' => 'Opcional para Grupos de Sociedades',
        ],
        [
            'id' => '624',
            'texto' => 'Coordinados',
        ],
        [
            'id' => '625',
            'texto' => 'Actividades Empresariales con ingresos a través de Plataformas Tecnológicas',
        ],
        [
            'id' => '626',
            'texto' => 'Simplificado de Confianza',
        ],
    ];

    public function getRegimen(): string
    {
        return $this->regimen;
    }

    public function getFechaAlta(): ?DateTimeImmutable
    {
        return $this->fechaAlta;
    }

    public function getRegimenId(): string
    {
        return $this->regimenId;
    }

    public function setRegimen(string $regimen): void
    {
        $this->regimen = $regimen;
        /** @var string $regimenAsText PHPStan: It is impossible here to return NULL*/
        $regimenAsText = preg_replace(['/^Régimen( de las| de los| de|) /u', '/ PM$/'], '', $regimen);
        $this->regimenId = $this->searchRegimenIdByText($regimenAsText);
    }

    public function setFechaAlta(string $fechaAlta): void
    {
        $date = DateTimeImmutable::createFromFormat('!d-m-Y', $fechaAlta);
        $this->fechaAlta = false !== $date ? $date : null;
    }

    private function searchRegimenIdByText(string $text): string
    {
        foreach ($this->regimenes as $regimen) {
            if (0 === strcmp(mb_strtoupper($text), mb_strtoupper($regimen['texto']))) {
                return $regimen['id'];
            }
        }
        return '';
    }

    /**
     * @return array{regimen: string, regimen_id: string, fecha_alta: ?DateTimeImmutable}
     */
    public function toArray(): array
    {
        return [
            'regimen' => $this->regimen,
            'regimen_id' => $this->regimenId,
            'fecha_alta' => $this->fechaAlta,
        ];
    }

    /**
     * @return array{regimen: string, regimen_id: string, fecha_alta: ?DateTimeImmutable}
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
