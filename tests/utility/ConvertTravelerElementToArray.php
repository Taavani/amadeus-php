<?php

namespace Amadeus\Tests\Utility;

use Amadeus\Amadeus;
use Amadeus\Client\HTTPClient;
use Amadeus\Client\Response;
use Amadeus\Resources\Resource;
use Amadeus\Resources\TravelerElement;
use Amadeus\Tests\PHPUnitUtil;
use PHPUnit\Framework\TestCase;

/**
 * This test covers the utility function toArray on the class TravelerElement.
 *
 * @covers \Amadeus\Resources\TravelerElement::__toArray()
 */
class ConvertTravelerElementToArray extends TestCase
{

    public function setUp(): void
    {
        // Mock an Amadeus with HTTPClient
        $amadeus = $this->createMock(Amadeus::class);
        $client = $this->createMock(HTTPClient::class);
        $amadeus->expects($this->any())
            ->method("getClient")
            ->willReturn($client);
    }

    /**
     * This test covers the utility function toArray on the class TravelerElement.
     *
     * @covers \Amadeus\Resources\TravelerElement::__toArray()
     *
     * @return void
     */
    public function test_convert_traveler_element_to_array()
    {
        $fileContent = PHPUnitUtil::readFile(
            PHPUnitUtil::RESOURCE_PATH_ROOT . "flight_offers_price_post_response_ok.json"
        );
        $data = json_decode($fileContent)->{'data'};
        $response = $this->createMock(Response::class);
        $response->expects($this->any())
            ->method("getData")
            ->willReturn($data);

        // Given2
        $body2 = PHPUnitUtil::readFile(
            PHPUnitUtil::RESOURCE_PATH_ROOT . "flight_offers_price_post_request2_ok.json"
        );

        $travelersArray = Resource::toResourceArray(
            json_decode($body2)->{'data'}->{'travelers'},
            TravelerElement::class
        );

        $traveler = $travelersArray[0];
        $this->assertIsArray($traveler->__toArray());
    }
}
