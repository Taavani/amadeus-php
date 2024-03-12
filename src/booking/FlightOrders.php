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
    /**
     * @var Amadeus
     */
    private Amadeus $amadeus;

    /**
     * @var CertificationHelper
     */
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
     * This function returns an order object, based on the flightOffer ID.
     *
     * @throws ResponseException
     */
    public function get(string $id)
    {
        try {
            $response = $this->amadeus
                ->getClient()
                ->getWithOnlyPath('/v1/booking/flight-orders/' . $id);

            // Save request file for certification purposes
            $this->certificationHelper->saveRequest(
                'Flight Get Order',
                $response,
                $response->getUrl()
            );

            // Save response file for certification purposes
            $this->certificationHelper->saveResponse(
                'Flight Get Order',
                $response,
                $response->getBody()
            );

            return Resource::fromObject($response, FlightOrder::class);

        } catch (ResponseException $exception) {
            $this->certificationHelper
                ->saveErrorRequest(
                    'Order not found',
                    $id
                );

            $this->certificationHelper
                ->saveErrorResponse(
                    'Order not found',
                    $exception
                );
            throw $exception;
        }
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

        // Save request file for certification purposes
        $this->certificationHelper->saveRequest(
            'Flight Get Order',
            $response,
            $response->getRequest()->getVerb() . ' ' . $response->getUrl()
        );

        // Save response file for certification purposes
        $this->certificationHelper->saveResponse(
            'Flight Get Order',
            $response,
            json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
        );

        return Resource::fromArray($response, FlightOrder::class);
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

        try {
            $response = $this->amadeus
                ->getClient()
                ->execute($request);

            // Save request file for certification purposes
            $this->certificationHelper->saveRequest(
                'Flight Order Issuance RQ',
                $response,
                $response->getUrl()
            );

            // Save request file for certification purposes
            $this->certificationHelper->saveResponse(
                'Flight Order Issuance RS',
                $response,
                json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
            );

            return Resource::fromObject($response, FlightOrder::class);
        } catch (ResponseException $exception) {
            $this->certificationHelper->saveErrorRequest(
                'Flight Create Order Error',
                $id
            );

            $this
                ->certificationHelper
                ->saveErrorResponse(
                    'Flight Order Issuance ERROR',
                    $exception
                );

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

            $this->certificationHelper->saveSuccess(
                $response,
                'Flight Create Order',
                json_decode($body)
            );

            return Resource::fromObject($response, FlightOrder::class);
        } catch (ResponseException $exception) {
            $this->certificationHelper->saveError(
                $exception,
                'Flight Create Order Error',
                json_decode($body)
            );

            throw $exception;
        }
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
