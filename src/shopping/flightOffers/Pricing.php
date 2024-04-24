<?php

declare(strict_types=1);

namespace Amadeus\Shopping\FlightOffers;

use Amadeus\Amadeus;
use Amadeus\CertificationHelper;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Resources\FlightOfferPricingOutput;
use Amadeus\Resources\Resource;

/**
 * A namespaced client for the
 * "/v1/shopping/flight-offers/pricing" endpoints.
 *
 * Access via the Amadeus client object.
 *
 *      $amadeus = Amadeus::builder("clientId", "secret")->build();
 *      $amadeus->getShopping()->getFlightOffers()->getPricing();
 *
 */
class Pricing
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
     * Flight Offers Price API:
     *
     *  The Flight Offers Price API allows the user to get or confirm the price of a flight
     *  and obtain information about taxes and fees to be applied to the entire journey.
     *  It is usually used after the Flight Offers Search API.
     *  It also retrieves ancillary information and the payment information details.
     *
     *      $amadeus->getShopping()->getFlightOffers()->getPricing()->post($body, $params); //$params is optional
     *
     * @link https://developers.amadeus.com/self-service/category/air/api-doc/flight-offers-price/api-reference
     * @link https://developers.amadeus.com/functional-doc-rest/49/api-docs-and-example/138985?apiVersionId=413
     *
     * @param string $body                  JSON body of flight offers as String to price
     * @param array|null $params            (optional)URL parameters such as include or forceClass
     * @return FlightOfferPricingOutput     an API resource
     * @throws ResponseException            when an exception occurs
     */
    public function post(string $body, ?array $params = null): object
    {

        try {
            $response = $this->amadeus->getClient()->postWithStringBody(
                '/v1/shopping/flight-offers/pricing',
                $body,
                $params
            );
            $this->certificationHelper->saveSuccess($response, 'Flight Offer Price', json_decode($body));

            return Resource::fromObject($response, FlightOfferPricingOutput::class);

        } catch (ResponseException $exception) {
            $this->certificationHelper->saveError($exception, 'Flight Offer Price Error', [ json_decode($body) ]);
            throw $exception;
        }
    }


    /**
     * Flight Offers Price API:
     *
     *  The Flight Offers Price API allows the user to get or confirm the price of a flight
     *  and obtain information about taxes and fees to be applied to the entire journey.
     *  It is usually used after the Flight Offers Search API.
     *  It also retrieves ancillary information and the payment information details.
     *
     *      // $payments, $travelers, $params are optional
     *      $amadeus->getShopping()->getFlightOffers()->getPricing()->post($flightOffers, $payments, $travelers $params);
     *
     * @link https://developers.amadeus.com/self-service/category/air/api-doc/flight-offers-price/api-reference
     * @link https://developers.amadeus.com/functional-doc-rest/49/api-docs-and-example/138985?apiVersionId=413
     *
     * @param array $flightOffers           Lists of flight offers as FlightOffer[]
     * @param array|null $payments          (optional) Lists of payments as FlightPayment[]
     * @param array|null $travelers         (optional) Lists of travelers as TravelerElement[]
     * @param array|null $params            (optional) URL parameters such as include or forceClass
     * @return FlightOfferPricingOutput     an API resource
     * @throws ResponseException            when an exception occurs
     */
    public function postWithFlightOffers(
        array $flightOffers,
        ?array $payments = null,
        ?array $travelers = null,
        ?array $params = null
    ): object {
        $flightOffersArray = array();
        foreach ($flightOffers as $flightOffer) {
            $flightOffersArray[] = json_decode((string)$flightOffer);
        }

        $paymentsArray = array();
        if ($payments != null) {
            foreach ($payments as $payment) {
                $paymentsArray[] = json_decode((string)$payment);
            }
        } else {
            $paymentsArray = null;
        }

        $travelersArray = array();
        if ($travelers != null) {
            foreach ($travelers as $traveler) {
                $travelersArray[] = json_decode((string)$traveler);
            }
        } else {
            $travelersArray = null;
        }

        // Prepare JSON object
        $flightPricingQuery = (object)[
            "data" => (object)[
                "type" => "flight-offers-pricing",
                "flightOffers" => $flightOffersArray,
                "payments" => $paymentsArray,
                "travelers" => $travelersArray
            ]
        ];

        $body = Resource::toString(get_object_vars($flightPricingQuery));

        return $this->post($body, $params);
    }
}
