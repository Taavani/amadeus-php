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

    public function get(string $id)
    {
        try {
            $response = $this->amadeus
                ->getClient()
                ->getWithOnlyPath('/v1/booking/flight-orders/' . $id);

            // Save request file for certification purposes
            $this->certificationHelper->saveRequest('Flight Get Order',
                $response->getHeaders()
                . PHP_EOL
                . $response->getUrl());

            // Save response file for certification purposes
            $this->certificationHelper->saveResponse('Flight Get Order',
                implode($response->getRequest()->getHeaders())
                . PHP_EOL
                . $response->getBody());

            return Resource::fromObject($response, FlightOrder::class);

        } catch (ResponseException $exception) {
            $this->certificationHelper->saveErrorResponse( 'Order not found', $exception->getMessage());
            throw $exception;
        }
    }

    /**
     *
     * @throws ResponseException
     */
    public function getByPNR(string $id)
    {
        $response = $this->amadeus
            ->getClient()
            ->getWithOnlyPath('/v1/booking/flight-orders/by-reference?reference=' . $id . '&originSystemCode=GDS');

        // Save request file for certification purposes
        $this->certificationHelper->saveRequest(
            'Flight Get Order',
            implode(PHP_EOL, $response->getRequest()->getHeaders()) .
            PHP_EOL .
            PHP_EOL .
            $response->getRequest()->getVerb() . ' ' . $response->getUrl()
        );

        // Save response file for certification purposes
        $this->certificationHelper->saveResponse('Flight Get Order',
            $response->getHeaders() .
            PHP_EOL .
            json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
        );

        return Resource::fromObject($response, FlightOrder::class);
    }

    /**
     *
     * @param string $id
     * @return mixed
     * @throws ResponseException
     */
    public function issueById(string $id)
    {

        $request = new Request(Constants::POST,
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

            // Save request file for certification purposes
            $this->certificationHelper->saveRequest(
                'Flight Order Issuance RQ',
                $response->getRequest()->getVerb() . ' ' . $response->getRequest()->getUri() .
                PHP_EOL .
                PHP_EOL .
                implode(PHP_EOL, $response->getRequest()->getHeaders()) .
                PHP_EOL .
                PHP_EOL .
                $response->getUrl());

            // Save request file for certification purposes
            $this->certificationHelper->saveResponse('Flight Order Issuance RS',
                $response->getRequest()->getVerb() . ' ' . $response->getRequest()->getUri() .
                PHP_EOL .
                PHP_EOL .
                implode(PHP_EOL, $response->getRequest()->getHeaders()) .
                PHP_EOL .
                PHP_EOL .
                json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
            );

            return Resource::fromObject($response, FlightOrder::class);
        } catch (ResponseException $exception) {
            $this
                ->certificationHelper
                ->saveErrorResponse('Flight Order Issuance ERROR', $exception->getMessage());

            throw $exception;
        }

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

        try {
            $response = $this->amadeus->getClient()->postWithStringBody(
                '/v1/booking/flight-orders',
                $body,
                $params
            );

            // Save request file for certification purposes
            $this->certificationHelper->saveRequest(
                'Flight Create Order',
                $response->getRequest()->getVerb() . ' ' . $response->getRequest()->getUri() .
                PHP_EOL .
                PHP_EOL .
                implode(PHP_EOL, $response->getRequest()->getHeaders()) .
                PHP_EOL .
                PHP_EOL .
                json_encode(json_decode($body), JSON_PRETTY_PRINT)
            );

        } catch (ResponseException $e) {

            $this->certificationHelper->saveRequest('Flight Create Order',
                implode($this->amadeus->getClient()->getConfiguration()->getAdditionalHeaders()) .
                PHP_EOL .
                $body);
            $this->certificationHelper->saveErrorResponse('Flight Create Order Error', $e->getMessage());
            throw $e;
        }

        // Save request file for certification purposes
        $this->certificationHelper->saveResponse('Flight Create Order',
            $response->getHeaders() .
            json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
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
