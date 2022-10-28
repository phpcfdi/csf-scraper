<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Persona\Traits;

trait PersonaTrait
{
    private function setAndGetProperty(string $complementFunction, mixed $value): mixed
    {
        $this->person->{$complementFunction} = $value;
        return $this->person->{$complementFunction};
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
}
