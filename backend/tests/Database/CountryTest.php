<?php

use IntruderAlert\Logger;
use IntruderAlert\Database\Country;

class CountryTest extends AbstractTestCase
{
    /** @var string $path Database path */
    private string $path = './backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb';

    /**
     * Test `lookup()`
     */
    public function testLookup(): void
    {
        $expected = [
            'country' => [
                'name' => 'United Kingdom',
                'code' => 'GB'
            ],
            'continent' => [
                'name' => 'Europe',
                'code' => 'EU'
            ]
        ];

        $database = new Country($this->path, new Logger());

        $this->assertEquals(
            $expected,
            $database->lookup('81.2.69.144')
        );
    }

    /**
     * Test `lookup()` with an Address not in the database
     */
    public function testLookupAddressNotFound(): void
    {
        $this->expectOutputRegex('/ Address not found in GeoIP2 country database/');

        $expected = [
            'country' => [
                'name' => 'Unknown',
                'code' => 'Unknown'
            ],
            'continent'  => [
                'name' => 'Unknown',
                'code' => 'Unknown'
            ]
        ];

        $database = new Country($this->path, new Logger());

        $this->assertEquals(
            $expected,
            $database->lookup('127.0.0.1')
        );
    }
}
