<?php

declare(strict_types=1);

namespace Amadeus\Shopping\FlightOffers;

use Amadeus\Amadeus;
use Amadeus\Exceptions\ResponseException;
use Amadeus\Resources\FlightOffer;
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

        $response = $this->amadeus->getClient()->postWithStringBody(
            '/v1/shopping/flight-offers/upselling',
            $body,
            $params
        );

        return Resource::fromArray($response, FlightOffer::class);
    }
}
