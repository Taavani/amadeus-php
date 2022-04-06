<?php

declare(strict_types=1);

namespace Amadeus\Tests;

use Amadeus\Airport\DirectDestinations;
use Amadeus\Amadeus;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Response;
use Amadeus\Shopping\Availability\FlightAvailabilities;
use PHPUnit\Framework\TestCase;

final class NamespaceTest extends TestCase
{
    /**
     * @return void
     */
    public function testAllNamespacesExist(): void
    {
        $client = Amadeus::builder("id", "secret")
            ->build();

        // Airport
        $this->assertNotNull($client->airport);
        $this->assertNotNull($client->airport->directDestinations);

        // Shopping
        $this->assertNotNull($client->shopping);
        $this->assertNotNull($client->shopping->availability);
        $this->assertNotNull($client->shopping->availability->flightAvailabilities);
    }

    private Amadeus $client;
    private array $params;
    private string $body;
    private Response $multiResponse;
    private Response $singleResponse;

    /**
     * @Before
     * @return void
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

    /**
     * @return void
     * @throws ResponseException
     */
    public function testGetMethods(): void
    {
        // Testing Airport Routes GET API
        /* @phpstan-ignore-next-line */
        $this->client->expects($this->any())
            ->method("get")
            ->with("/v1/airport/direct-destinations", $this->params)
            ->willReturn($this->multiResponse);
        $directDestinations = new DirectDestinations($this->client);
        $this->assertNotNull($directDestinations->get($this->params));
        $this->assertEquals(2, sizeof($directDestinations->get($this->params)));
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function testPostMethods(): void
    {
        // Testing Flight Availabilities POST API
        /* @phpstan-ignore-next-line */
        $this->client->expects($this->any())
            ->method("post")
            ->with("/v1/shopping/availability/flight-availabilities", $this->body)
            ->willReturn($this->multiResponse);
        $flightAvailabilities = new FlightAvailabilities($this->client);
        $this->assertNotNull($flightAvailabilities->post($this->body));
        $this->assertEquals(2, sizeof($flightAvailabilities->post($this->body)));
    }
}
