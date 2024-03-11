<?php

declare(strict_types=1);

namespace Amadeus\Tests;

use Amadeus\AmadeusBuilder;
use Amadeus\Client\AccessToken;
use Amadeus\Client\BasicHTTPClient;
use Amadeus\Configuration;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Class AmadeusBuilderTest
 * @package Amadeus\Tests
 */
#[CoversClass(AmadeusBuilder::class)]
#[CoversClass(AccessToken::class)]
#[CoversClass(BasicHTTPClient::class)]
#[CoversClass(Configuration::class)]
final class AmadeusBuilderTest extends TestCase
{
    private AmadeusBuilder $amadeusBuilder;
    private BasicHTTPClient $httpClient;

    #[Before]
    public function setUp(): void
    {
        $configuration = new Configuration('id', 'secret');
        $this->httpClient = new BasicHTTPClient($configuration);
        $this->amadeusBuilder = new AmadeusBuilder($configuration);

        $this->amadeusBuilder
            ->setSsl(true)
            ->setPort(8080)
            ->setHost('localhost')
            ->setHttpClient($this->httpClient)
            ->setTimeout(20);
    }

    #[Test]
    public function initialize(): void
    {
        $configuration = $this->amadeusBuilder->getConfiguration();
        $this->assertTrue($configuration->isSsl());
        $this->assertEquals(8080, $configuration->getPort());
        $this->assertEquals('localhost', $configuration->getHost());
        $this->assertEquals($this->httpClient, $this->amadeusBuilder->getConfiguration()->getHttpClient());
        $this->assertEquals(20, $this->amadeusBuilder->getConfiguration()->getTimeout());
    }

    #[Test]
    public function buildWithProductionEnvironment(): void
    {
        $this->amadeusBuilder->setProductionEnvironment();
        $configuration = $this->amadeusBuilder->getConfiguration();
        $this->assertEquals('api.amadeus.com', $configuration->getHost());
    }
}
