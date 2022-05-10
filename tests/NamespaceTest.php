<?php

declare(strict_types=1);

namespace Amadeus\Tests;

use Amadeus\Airport\DirectDestinations;
use Amadeus\Amadeus;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Response;
use Amadeus\Shopping\Availability\FlightAvailabilities;
use Amadeus\Shopping\FlightOffers;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Amadeus\Configuration
 * @covers \Amadeus\Amadeus
 * @covers \Amadeus\HTTPClient
 * @covers \Amadeus\Client\AccessToken
 * @covers \Amadeus\Resources\Resource
 * @covers \Amadeus\Airport
 * @covers \Amadeus\Airport\DirectDestinations
 * @covers \Amadeus\Shopping
 * @covers \Amadeus\Shopping\Availability
 * @covers \Amadeus\Shopping\Availability\FlightAvailabilities
 * @covers \Amadeus\Shopping\FlightOffers
 */
final class NamespaceTest extends TestCase
{
    public function testAllNamespacesExist(): void
    {
        $client = Amadeus::builder("id", "secret")->build();

        // Airport
        $this->assertNotNull($client->airport);
        $this->assertNotNull($client->airport->directDestinations);

        // Shopping
        $this->assertNotNull($client->shopping);
        $this->assertNotNull($client->shopping->availability);
        $this->assertNotNull($client->shopping->availability->flightAvailabilities);
        $this->assertNotNull($client->shopping->flightOffers);
    }

    private Amadeus $client;
    private array $params;
    private string $body;
    private Response $multiResponse;
    private Response $singleResponse;

    /**
     * @Before
     */
    public function setUp(): void
    {
        $this->client = $this->createMock(Amadeus::class);
        $this->params = array("airline"=>"1X");
        $this->body = "{ \"data\": [{}]}";

        // Prepare a plural response
        $jsonArray = array();
        $jsonArray[] = (object)[];
        $jsonArray[] = (object)[];
        $this->multiResponse = $this->createMock(Response::class);
        $this->multiResponse->expects($this->any())
            ->method("getData")
            ->willReturn($jsonArray);

        // Prepare a single response
        $jsonObject = (object)[];
        $jsonObject->foo = "bar";
        $this->singleResponse = $this->createMock(Response::class);
        $this->singleResponse->expects($this->any())
            ->method("getData")
            ->willReturn($jsonObject);
    }

    // ------ GET ------
    /**
     * @throws ResponseException
     */
    public function testAirportRoutesGetMethod(): void
    {
        /* @phpstan-ignore-next-line */
        $this->client->expects($this->any())
            ->method("getWithArrayParams")
            ->with("/v1/airport/direct-destinations", $this->params)
            ->willReturn($this->multiResponse);
        $directDestinations = new DirectDestinations($this->client);
        $this->assertNotNull($directDestinations->get($this->params));
        $this->assertEquals(2, sizeof($directDestinations->get($this->params)));
    }

    /**
     * @throws ResponseException
     */
    public function testFlightOffersSearchGetMethod(): void
    {
        /* @phpstan-ignore-next-line */
        $this->client->expects($this->any())
            ->method("getWithArrayParams")
            ->with("/v2/shopping/flight-offers", $this->params)
            ->willReturn($this->multiResponse);
        $flightOffers = new FlightOffers($this->client);
        $this->assertNotNull($flightOffers->get($this->params));
        $this->assertEquals(2, sizeof($flightOffers->get($this->params)));
    }

    // ------ POST ------
    /**
     * @throws ResponseException
     */
    public function testFlightAvailabilitiesPostMethod(): void
    {
        /* @phpstan-ignore-next-line */
        $this->client->expects($this->any())
            ->method("postWithStringBody")
            ->with("/v1/shopping/availability/flight-availabilities", $this->body)
            ->willReturn($this->multiResponse);
        $flightAvailabilities = new FlightAvailabilities($this->client);
        $this->assertNotNull($flightAvailabilities->post($this->body));
        $this->assertEquals(2, sizeof($flightAvailabilities->post($this->body)));
    }

    /**
     * @throws ResponseException
     */
    public function testFlightOffersSearchPostMethod(): void
    {
        /* @phpstan-ignore-next-line */
        $this->client->expects($this->any())
            ->method("postWithStringBody")
            ->with("/v2/shopping/flight-offers", $this->body)
            ->willReturn($this->multiResponse);
        $flightOffers = new FlightOffers($this->client);
        $this->assertNotNull($flightOffers->post($this->body));
        $this->assertEquals(2, sizeof($flightOffers->post($this->body)));
    }
}
