<?php

declare(strict_types=1);

namespace Amadeus\Booking;

use Amadeus\Amadeus;
use Amadeus\Client\Request;
use Amadeus\Constants;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Resources\FlightOrder;
use Amadeus\Resources\Resource;

/**
 * A namespaced client for the
 * "/v1/booking/flight-orders" endpoints.
 *
 * Access via the Amadeus client object.
 *
 *      $amadeus = Amadeus::builder("clientId", "secret")->build();
 *      $amadeus->getBooking()->getFlightOrders();
 *
 */
class FlightOrders
{
    /**
     * @var Amadeus
     */
    private Amadeus $amadeus;

    /**
     * Constructor
     * @param Amadeus $amadeus
     */
    public function __construct(Amadeus $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    /**
     * This function returns an order object, based on the flightOffer ID.
     *
     * @throws ResponseException
     */
    public function get(string $id)
    {
        $response = $this->amadeus
            ->getClient()
            ->getWithOnlyPath('/v1/booking/flight-orders/' . $id);

        return Resource::fromObject($response, FlightOrder::class);
    }

    /**
     * This function returns an order object, based on the PNR
     *
     * @throws ResponseException
     */
    public function getByPNR(string $id)
    {
        $response = $this->amadeus
            ->getClient()
            ->getWithOnlyPath('/v1/booking/flight-orders/by-reference?reference=' . $id . '&originSystemCode=GDS');

        return Resource::fromArray($response, FlightOrder::class)[0];
    }

    /**
     * Flight Issue API:
     *
     * This operation allows to trigger a basic issuance of a given flight-order of full service carriers (FSC) or NDC.
     * The full flightOrder is retrieved in response and will contain the newly created tickets with their number
     * and status.
     *
     * The Flight Issue API allows you to perform an order issue.
     *
     *      $amadeus->getBooking()->getFlightOrders()->postIssue($id);
     *
     * @link https://developers.amadeus.com/functional-doc-rest/89/api-docs-and-example/138988?apiVersionId=416
     *
     * @param string $id
     * @return mixed
     * @throws ResponseException
     */
    public function postIssue(string $id): mixed
    {

        $request = new Request(
            Constants::POST,
            '/v1/booking/flight-orders/' . $id . '/issuance',
            null,
            null,
            $this->amadeus->getClient()->getAccessToken()->getBearerToken(),
            $this->amadeus->getClient()
        );

        $response = $this->amadeus
            ->getClient()
            ->execute($request);

        return Resource::fromObject($response, FlightOrder::class);
    }

    /**
     * Flight Create Orders API:
     *
     * The Flight Create Orders API allows you to perform flight booking.
     *
     *      $amadeus->getBooking()->getFlightOrders()->post($body);
     *
     * @link https://developers.amadeus.com/self-service/category/air/api-doc/flight-offers-search/api-reference
     *
     * @param string $body the parameters to send to the API as a String
     * @return FlightOrder          an API resource
     * @throws ResponseException    when an exception occurs
     */
    public function post(string $body, ?array $params = null): object
    {

        $response = $this->amadeus->getClient()->postWithStringBody(
            '/v1/booking/flight-orders',
            $body,
            $params
        );

        return Resource::fromObject($response, FlightOrder::class);
    }

    /**
     * Flight Create Orders API:
     *
     * The Flight Create Orders API allows you to perform flight booking.
     *
     *      $amadeus->getBooking()->getFlightOrders()->post($fightOffer, $travelers);
     *
     * @link https://developers.amadeus.com/self-service/category/air/api-doc/flight-offers-search/api-reference
     *
     * @param array $flightOffers Lists of flight offers as FlightOffer[]
     * @param array $travelers List of travelers as TravelerElement[]
     * @return FlightOrder          an API resource
     * @throws ResponseException    when an exception occurs
     */
    public function postWithFlightOffersAndTravelers(
        array $flightOffers,
        array $travelers
    ): object {
        $flightOffersArray = array();
        foreach ($flightOffers as $flightOffer) {
            $flightOffersArray[] = json_decode((string)$flightOffer);
        }

        $travelersArray = array();
        foreach ($travelers as $traveler) {
            $travelersArray[] = json_decode((string)$traveler);
        }

        // Prepare the post JSON body
        $flightOrderQuery = (object)[
            "data" => (object)[
                "type" => "flight-offers-pricing",
                "flightOffers" => $flightOffersArray,
                "travelers" => $travelersArray
            ]
        ];

        $body = Resource::toString(get_object_vars($flightOrderQuery));

        return $this->post($body);
    }
}
