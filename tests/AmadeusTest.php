<?php

declare(strict_types=1);

namespace Amadeus\Tests;

use Amadeus\Amadeus;
use Amadeus\AmadeusBuilder;
use Amadeus\Client\AccessToken;
use Amadeus\Configuration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * Class AmadeusTest
 * @package Amadeus\Tests
 */
#[CoversClass(AmadeusBuilder::class)]
#[CoversClass(AccessToken::class)]
#[CoversClass(Configuration::class)]
final class AmadeusTest extends TestCase
{
    public function testBuilder(): void
    {
        $this->assertTrue(
            Amadeus::builder("id", "secret") instanceof AmadeusBuilder,
            "should return a AmadeusBuilder instance"
        );
    }

    public function testBuilderWithNullClientId(): void
    {
        $this->expectException(TypeError::class);
        Amadeus::builder(null, "secret")->build();
    }

    public function testBuilderWithNullClientSecret(): void
    {
        $this->expectException(TypeError::class);
        Amadeus::builder("id", null)->build();
    }

    public function testBuilderWithEnvironment(): void
    {
        // Set the environment variable
        putenv("AMADEUS_CLIENT_ID=MY_CLIENT_ID");
        putenv("AMADEUS_CLIENT_SECRET=MY_CLIENT_SECRET");

        $this->assertTrue(
            Amadeus::builder() instanceof AmadeusBuilder,
            "should return a AmadeusBuilder instance"
        );

        // Unset the environment variable
        putenv("AMADEUS_CLIENT_ID");
        putenv("AMADEUS_CLIENT_SECRET");
    }
}
