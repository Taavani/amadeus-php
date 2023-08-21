<?php

declare(strict_types=1);

namespace Amadeus\Booking;

use Amadeus\Amadeus;
use Amadeus\CertificationHelper;
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
    private Amadeus $amadeus;

    private CertificationHelper $certificationHelper;

    /**
     * Constructor
     * @param Amadeus $amadeus
     */
    public function __construct(Amadeus $amadeus)
    {
        $this->amadeus = $amadeus;
        $this->certificationHelper = new CertificationHelper($amadeus);

    }

    /**
     * @throws ResponseException
     */
    public function get(string $id)
    {

        $response = $this->amadeus
            ->getClient()
            ->getWithOnlyPath('/v1/booking/flight-orders/' . $id);

        // Save request file for certification purposes
        $this->certificationHelper->saveRequest( 'Flight Get Order' , $response->getUrl());

        // Save response file for certification purposes
        $this->certificationHelper->saveResponse( 'Flight Get Order', $response->getBody());

        return Resource::fromObject($response, FlightOrder::class);
    }

    public function getByPNR(string $id)
    {
        $response = $this->amadeus
            ->getClient()
            ->getWithOnlyPath('/v1/booking/flight-orders/by-reference?reference=' . $id . '&originSystemCode=GDS');

        // Save request file for certification purposes
        $this->certificationHelper->saveRequest( 'Flight Get Order' , $response->getUrl());

        // Save response file for certification purposes
        $this->certificationHelper->saveResponse( 'Flight Get Order', $response->getBody());

        return Resource::fromObject($response, FlightOrder::class);
    }

    public function issueById(string $id) {

        $request = new Request(Constants::POST ,
            '/v1/booking/flight-orders/' . $id . '/issuance',
            null,

            null,
            $this->amadeus->getClient()->getAccessToken()->getBearerToken(),
            $this->amadeus->getClient()
        );

        try {
            $response = $this->amadeus
                ->getClient()
                ->execute($request);

        } catch (ResponseException $exception) {
            file_put_contents('Flight Order Issuance ERROR RS.json', $exception->getMessage());
        }

        $counter = 0;
        while ($counter >= 0) {
            if (!file_exists($counter . ' - Flight Order Issuance RQ.json')) {
                file_put_contents($counter . ' - Flight Order Issuance RQ.json', $response->getUrl());
                $counter = -1;
            } else {
                $counter++;
            }
        }


        $counter = 0;
        while ($counter >= 0) {
            if (!file_exists($counter . ' - Flight Order Issuance RS.json')) {
                file_put_contents($counter . ' - Flight Order Issuance RS.json', $response->getBody());
                $counter = -1;
            } else {
                $counter++;
            }
        }


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
    public function post(string $body): object
    {

        // Save request file for certification purposes
        $this->certificationHelper->saveRequest('Flight Create Order', $body);

        try {
            $response = $this->amadeus->getClient()->postWithStringBody(
                '/v1/booking/flight-orders',
                $body
            );
        } catch (ResponseException $e) {
            $this->certificationHelper->saveErrorResponse('Flight Create Order Error', $e->getMessage());
            throw $e;
        }

        // Save request file for certification purposes
        $this->certificationHelper->saveResponse('Flight Create Order',
            $response->getHeaders() .
            PHP_EOL .
            $response->getBody());

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
    ): object
    {
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
