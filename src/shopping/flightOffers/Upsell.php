<?php

declare(strict_types=1);

namespace Amadeus\Shopping\FlightOffers;

use Amadeus\Amadeus;
use Amadeus\CertificationHelper;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Resources\FlightOffer;
use Amadeus\Resources\FlightOfferPricingOutput;
use Amadeus\Resources\Resource;

/**
 * A namespaced client for the
 *  "/v1/shopping/flight-offers/upselling" endpoints.
 *
 * Access via the Amadeus client object.
 *
 *      $amadeus = Amadeus::builder("clientId", "secret")->build();
 *      $amadeus->getShopping()->getFlightOffers()->getUpsell();
 *
 */
class Upsell
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
     * Flight Offers Upsell
     *
     * The UpsellBrandedFares REST/JSON service enables you to get upgraded Branded Fares flight recommendations
     * available from a flight offer. Branded Fares are airline offers in which airline bundles its airfares with
     * options and features, such as refundability and miles accrual, or a pre-reserved seat, baggage, and meal.
     * It is usually used after the Flight Offers Search service.
     *
     * @param string $body The required request body.
     * @param array|null $params Optional parameters
     * @return FlightOffer[]
     * @throws ResponseException
     */
    public function post(string $body, ?array $params = null): array
    {

        try {
            $response = $this->amadeus->getClient()->postWithStringBody(
                '/v1/shopping/flight-offers/upselling',
                $body,
                $params
            );

            $this->certificationHelper->saveSuccess($response, 'Flight Offer Upsell', json_decode($body));

            return Resource::fromArray($response, FlightOffer::class);

        } catch (ResponseException $exception) {

            $this->certificationHelper->saveError($exception, 'Flight Offer Upsell Error', json_decode($body));

            $this->certificationHelper->saveErrorResponse(
                'Flight Offer Upsell Error',
                $exception
            );
            throw $exception;
        }
    }
}
