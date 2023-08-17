<?php

declare(strict_types=1);

namespace Amadeus\Booking;

use Amadeus\Amadeus;
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
     * Flight Create Orders API:
     *
     * The Flight Create Orders API allows you to perform flight booking.
     *
     *      $amadeus->getBooking()->getFlightOrders()->post($body);
     *
     * @link https://developers.amadeus.com/self-service/category/air/api-doc/flight-offers-search/api-reference
     *
     * @param  string $body         the parameters to send to the API as a String
     * @return FlightOrder          an API resource
     * @throws ResponseException    when an exception occurs
     */
    public function post(string $body): object
    {

        // Save request file for certification purposes
        if (strcasecmp('certification', $this->amadeus->getClient()->getConfiguration()->getLogLevel()) === 0) {
            $counter = 0;
            while ($counter >= 0) {
                if (!file_exists($counter . ' - Flight Create Order RQ.json')) {
                    file_put_contents($counter . ' - Flight Create Order RQ.json', $body);
                    $counter = -1;
                } else {
                    $counter++;
                }
            }
        }

        try {
            $response = $this->amadeus->getClient()->postWithStringBody(
                '/v1/booking/flight-orders',
                $body
            );
        } catch (ResponseException $e) {
            if (strcasecmp('certification', $this->amadeus->getClient()->getConfiguration()->getLogLevel()) === 0) {
                file_put_contents('Flight Create Order Error.json', $e);
            }
            throw $e;
        }

        // Save request file for certification purposes
        if (strcasecmp('certification', $this->amadeus->getClient()->getConfiguration()->getLogLevel()) === 0) {
            $counter = 0;
            while ($counter >= 0) {
                if (!file_exists($counter . ' - Flight Create Order RS.json')) {
                    file_put_contents($counter . ' - Flight Create Order RS.json', $response->getBody());
                    $counter = -1;
                } else {
                    $counter++;
                }
            }
        }

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
     * @param array $flightOffers   Lists of flight offers as FlightOffer[]
     * @param array $travelers      List of travelers as TravelerElement[]
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
