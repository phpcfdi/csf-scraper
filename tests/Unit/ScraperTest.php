<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PhpCfdi\CsfScraper\Tests\Unit;

use GuzzleHttp\Client;
use PhpCfdi\CsfScraper\Scraper;
use PhpCfdi\CsfScraper\Tests\TestCase;

final class ScraperTest extends TestCase
{
    public function test_create_with_specific_openssl_cipher_list(): void
    {
        $scraper = Scraper::create();
        $client = $scraper->getClient();
        $this->assertInstanceOf(Client::class, $client);

        /**
         * Problem: The method getConfig is deprecated
         * @see Client::getConfig()
         */
        $this->assertSame(
            [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
            $client->getConfig('curl'),
        );
        $this->assertSame(10.0, $client->getConfig('timeout'));
    }

    public function test_create_with_timeout(): void
    {
        $scraper = Scraper::create(5.0);
        $client = $scraper->getClient();

        $this->assertSame(5.0, $client->getConfig('timeout'));
    }
}
