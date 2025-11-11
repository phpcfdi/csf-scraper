<?php

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit\Persona;

use DateTimeImmutable;
use PhpCfdi\CsfScraper\PersonaMoral;
use PhpCfdi\CsfScraper\Tests\TestCase;
use PhpCfdi\CsfScraper\Tests\Unit\Persona\Traits\PersonaTrait;
use PHPUnit\Framework\Attributes\DataProvider;

class PersonaMoralPropertiesTest extends TestCase
{
    use PersonaTrait;

    private PersonaMoral $person;

    public function setUp(): void
    {
        parent::setUp();
        $this->person = new PersonaMoral();
    }

    /** @return array<int, array<int, string>> */
    public static function datePropertiesProvider(): array
    {
        return [
            ['fechaConstitucion', '16-05-1996'],
        ];
    }

    /** @return array<int, array<int, string>> */
    public static function stringPropertiesProvider(): array
    {
        return [
            ['RazonSocial', 'MI TIENDITA'],
            ['RegimenDeCapital', 'SA de SV'],
        ];
    }

    #[DataProvider('stringPropertiesProvider')]
    public function test_set_and_get_string_properties(string $complementFunction, string $value): void
    {
        $result = $this->setAndGetProperty($complementFunction, $value);
        $this->assertSame($value, $result);
    }

    #[DataProvider('datePropertiesProvider')]
    public function test_set_and_get_date_properties(string $complementFunction, string $value): void
    {
        $result = $this->setAndGetProperty($complementFunction, $value);
        $this->assertEquals(DateTimeImmutable::createFromFormat('!d-m-Y', $value), $result);
    }
}
