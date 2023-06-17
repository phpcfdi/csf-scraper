<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Persona\Traits;

use PhpCfdi\CsfScraper\Regimen;

trait PersonaTrait
{
    private function setAndGetProperty(string $complementFunction, mixed $value): mixed
    {
        $this->person->{$complementFunction} = $value;
        return $this->person->{$complementFunction};
    }

    public function test_allows_more_than_one_regimen(): void
    {
        $first = new Regimen();
        $first->setRegimen('Simplificado de confianza');
        $second = new Regimen();
        $second->setRegimen('Demás ingresos');

        $person = $this->person;
        $person->addRegimenes($first, $second);

        $this->assertSame(
            [$first, $second],
            $person->getRegimenes()->getRegimenes(),
        );
    }

    /**
    *
    * test to check if new property not contained in Persona is found is assigned and value can be retrieved
    * its goal is not to be used but to prevent unexpected exceptions
    * */
    public function test_assign_dynamic_property(): void
    {
        /** @phpstan-ignore-next-line */
        $this->person->{'dynamic'} = 'dynamic';
        $this->assertSame('dynamic', $this->person->{'dynamic'});
    }

    public function test_allows_more_than_one_regimen(): void
    {
        $first = new Regimen();
        $first->setRegimen('Simplificado de confianza');
        $second = new Regimen();
        $second->setRegimen('Demás ingresos');

        $person = $this->person;
        $person->addRegimenes($first, $second);

        $this->assertSame(
            [$first, $second],
            $person->getRegimenes()->getRegimenes(),
        );
    }
}
