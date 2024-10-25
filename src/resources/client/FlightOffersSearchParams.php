<?php
/**
 * This file contains the class FlightOffersSearchParams
 *
 * @package Amadeus\Resources\Client
 * @version 0.4.0
 */

namespace Amadeus\Resources\Client;

use stdClass;

/**
 * A class to hold the search parameters for the Flight Offers Search API. The class has helper functions to generate
 * correct parameter for the endpoints:
 *
 * GET      /shopping/flight-offers
 * POST     /shopping/flight-offers
 *
 * Given the complexity of the POST function there are a number of properties in the class, that are not used
 * for the GET function.
 *
 */
class FlightOffersSearchParams
{
    /**
     * city/airport IATA code from which the traveler will depart, e.g. BOS for Boston
     *
     * Example : SYD
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string|null
     */
    private string|null $originLocationCode;

    /**
     * city/airport IATA code to which the traveler is going, e.g. PAR for Paris
     *
     * Example : BKK
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string|null
     */
    private string|null $destinationLocationCode;

    /**
     * the date on which the traveler will depart from the origin to go to the destination. Dates are specified in the
     * ISO 8601 YYYY-MM-DD format, e.g. 2017-12-25
     *
     * Example : 2023-05-02
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string|null
     */
    private string|null $departureDate;

    /**
     * the date on which the traveler will depart from the destination to return to the origin. If this parameter is
     * not specified, only one-way itineraries are found. If this parameter is specified, only round-trip itineraries
     * are found. Dates are specified in the ISO 8601 YYYY-MM-DD format, e.g. 2018-02-28
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string|null
     */
    private string|null $returnDate;

    /**
     * the number of adult travelers (age 12 or older on date of departure).
     *
     * The total number of seated travelers (adult and children) can not exceed 9.
     *
     * This property is used both for the GET and the POST function.
     *
     * @var int
     */
    private int $adults;

    /**
     * the number of child travelers (older than age 2 and younger than age 12 on date of departure) who will each
     * have their own separate seat. If specified, this number should be greater than or equal to 0
     *
     * The total number of seated travelers (adult and children) can not exceed 9.
     *
     * This property is used both for the GET and the POST function.
     *
     * @var int
     */
    private int $children;

    /**
     * the number of infant travelers (whose age is less or equal to 2 on date of departure). Infants travel on the
     * lap of an adult traveler, and thus the number of infants must not exceed the number of adults. If specified,
     * this number should be greater than or equal to 0
     *
     * This property is used both for the GET and the POST function.
     *
     * @var int
     */
    private int $infants;

    /**
     * most of the flight time should be spent in a cabin of this quality or higher. The accepted travel class is
     * economy, premium economy, business or first class. If no travel class is specified, the search considers
     * any travel class
     *
     * Available values : ECONOMY, PREMIUM_ECONOMY, BUSINESS, FIRST
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string|null
     */
    private string|null $travelClass;

    /**
     * This option ensures that the system will ignore these airlines. This can not be cumulated with parameter includedAirlineCodes.
     *
     * Airlines are specified as IATA airline codes and are comma-separated, e.g. 6X,7X,8X
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string
     */
    private string $includedAirlineCodes;

    /**
     * This option ensures that the system will ignore these airlines. This can not be cumulated with parameter includedAirlineCodes.
     *
     * Airlines are specified as IATA airline codes and are comma-separated, e.g. 6X,7X,8X
     *
     * This property is used both for the GET and the POST function.
     *
     * @var string
     */
    private string $excludedAirlineCodes;

    /**
     * if set to true, the search will find only flights going from the origin to the destination with no stop in between
     *
     * This property is used both for the GET and the POST function.
	 *
     * @var bool
     */
    private bool $nonStop;

	/**
	 * The maximal number of connections for each itinerary. Value can be 0, 1 or 2.
	 *
	 * This property is only used in the POST function.
	 *
	 * @var int $stop
	 */
	private int $stop;

    /**
     * the preferred currency for the flight offers. Currency is specified in the ISO 4217 format, e.g. EUR for Euro
     *
     * @var string
     */
    private string $currencyCode;

    /**
     * maximum price per traveler. By default, no limit is applied. If specified, the value should be a positive number with no decimals
     *
     * @var int
     */
    private int $maxPrice;

    /**
     * maximum number of flight offers to return. If specified, the value should be greater than or equal to 1
     *
     * @var int
     */
    private int $max;

    /**
     * This is the constructor of the class FlightOffersSearchParams. All the required properties are set to null or 0.
     */
    public function __construct()
    {
        $this->originLocationCode = null;
        $this->destinationLocationCode = null;
        $this->departureDate = null;
        $this->returnDate = null;
        $this->adults = 0;
        $this->children = 0;
        $this->infants = 0;
        $this->travelClass = null;
        $this->includedAirlineCodes = '';
        $this->excludedAirlineCodes = '';
        $this->nonStop = false;
        $this->currencyCode = 'EUR';
        $this->maxPrice = 0;
        $this->max = 0;
    }

    /**
     * @param string $originLocationCode
     *
     * @return $this
     */
    public function setOriginLocationCode(string $originLocationCode): FlightOffersSearchParams
    {
        $this->originLocationCode = $originLocationCode;

        return $this;
    }

    /**
     * @param string $destinationLocationCode
     *
     * @return $this
     */
    public function setDestinationLocationCode(string $destinationLocationCode): FlightOffersSearchParams
    {
        $this->destinationLocationCode = $destinationLocationCode;
        return $this;
    }

    /**
     * @param string $departureDate
     *
     * @return $this
     */
    public function setDepartureDate(string $departureDate): FlightOffersSearchParams
    {
        $this->departureDate = $departureDate;
        return $this;
    }

    /**
     * @param string $returnDate
     *
     * @return $this
     */
    public function setReturnDate(string $returnDate): FlightOffersSearchParams
    {
        $this->returnDate = $returnDate;
        return $this;
    }

    /**
     * @param int $adults
     *
     * @return $this
     */
    public function setAdults(int $adults): FlightOffersSearchParams
    {
        $this->adults = $adults;
        return $this;
    }

    /**
     * @param int $children
     *
     * @return $this
     */
    public function setChildren(int $children): FlightOffersSearchParams
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @param int $infants
     *
     * @return $this
     */
    public function setInfants(int $infants): FlightOffersSearchParams
    {
        $this->infants = $infants;
        return $this;
    }

    /**
     * @param string $travelClass
     *
     * @return $this
     */
    public function setTravelClass(string $travelClass): FlightOffersSearchParams
    {
        $this->travelClass = $travelClass;
        return $this;
    }

    /**
     * @param string $includedAirlineCodes
     *
     * @return $this
     */
    public function setIncludedAirlineCodes(string $includedAirlineCodes): FlightOffersSearchParams
    {
        $this->includedAirlineCodes = $includedAirlineCodes;
        return $this;
    }

    /**
     * @param string $excludedAirlineCodes
     *
     * @return $this
     */
    public function setExcludedAirlineCodes(string $excludedAirlineCodes): FlightOffersSearchParams
    {
        $this->excludedAirlineCodes = $excludedAirlineCodes;
        return $this;
    }

    /**
     * @param bool $nonStop
     *
     * @return $this
     */
    public function setNonStop(bool $nonStop): FlightOffersSearchParams
    {
        $this->nonStop = $nonStop;
        return $this;
    }

    /**
     * @param string $currencyCode
     *
     * @return $this
     */
    public function setCurrencyCode(string $currencyCode): static
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @param int $maxPrice
     *
     * @return $this
     */
    public function setMaxPrice(int $maxPrice): FlightOffersSearchParams
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @param int $max
     *
     * @return $this
     */
    public function setMax(int $max): FlightOffersSearchParams
    {
        $this->max = $max;
        return $this;
    }

    /**
     * This function returns an array that can be used as a parameter for the Flight Offers Search API.
     *
     * @return array
     */
    public function toSearchArray(): array
    {
        // Make sure that originLocationCode, destinationLocationCode, departureDate and adults are set
        if (!$this->originLocationCode || !$this->destinationLocationCode || !$this->departureDate || 0 < $this->adults) {
            throw new \InvalidArgumentException("originLocationCode, destinationLocationCode, departureDate and max must be set");
        }

        $params = [
            "originLocationCode" => $this->originLocationCode,
            "destinationLocationCode" => $this->destinationLocationCode,
            "departureDate" => $this->departureDate,
            "adults" => $this->adults,
            "currencyCode" => $this->currencyCode
        ];

        if (!$this->returnDate) {
            $params["returnDate"] = $this->returnDate;
        }

        if (0 < $this->children) {
            $params["children"] = $this->children;
        }

        if (($this->infants > 0) && ($this->infants <= $this->adults)) {
            $params["infants"] = $this->infants;
        }

        if (!$this->travelClass) {
            $params["travelClass"] = $this->travelClass;
        }

        if (!$this->includedAirlineCodes) {
            $params["includedAirlineCodes"] = $this->includedAirlineCodes;
        }

        if ($this->excludedAirlineCodes) {
            $params['excludedAirlineCodes'] = $this->excludedAirlineCodes;
        }

        if ($this->nonStop) {
            $params['nonStop'] = $this->nonStop;
        }

        if (0 < $this->maxPrice) {
            $params['maxPrice'] = $this->maxPrice;
        }

        if (0 < $this->max) {
            $params['max'] = $this->max;
        }

        return $params;
    }

	/**
	 * @return string|false
	 */
	public function toSearchString(): bool|string {
		$params = new stdClass();
		$params->currencyCode = $this->currencyCode;
		$params->sources = [
			"GDS",
			"PYTON"
		];

		// Build originDestinations
		$originDestinations = [];

		// Outbound
		$originDestinations[] = [
			'id' => 0,
			'originLocationCode' => $this->originLocationCode,
			'destinationLocationCode' => $this->destinationLocationCode,
			'departureDateTimeRange' => [
				'date' =>  $this->departureDate,
			]
		];
		// Homebound
		if ($this->returnDate) {
			$originDestinations[] = [
				'id' => 1,
				'originLocationCode' => $this->destinationLocationCode,
				'destinationLocationCode' => $this->originLocationCode,
				'departureDateTimeRange' => [
					'date' =>  $this->returnDate,
				]
			];
		}
		$params->originDestinations = $originDestinations;

		// Build the travellers
		$travellers = [];
		$index = 0;

		for ($i = 0; $i < $this->adults; $i++) {
			$travellers[] = [
				'id' => $index,
				'travelerType' => 'ADULT'
			];
			$index++;
		}

		if ($this->children > 0) {
			for ($i = 0; $i < $this->children; $i++) {
				$travellers[] = [
					'id' => $index,
					'travelerType' => 'CHILD'
				];
				$index++;
			}
		}

		if ($this->infants > 0) {
			for ($i = 0; $i < $this->infants; $i++) {
				$travellers[] = [
					'id' => $index,
					'travelerType' => 'INFANT'
				];
				$index++;
			}
		}
		$params->travelers = $travellers;

		// Build the search criteria
		$searchCriteria = [
			"additionalInformation" => [
				"brandedFares" => true,
				"fareRules" => true,
			],
			"pricingOptions" => [
				"fareType" => [
					"PUBLISHED"
				]
			]
		];
		$flightFilters = [];

		if (!is_null($this->travelClass)) {
			$flightFilters['cabinRestrictions'] = [
				(object) [
					'cabin' => strtoupper($this->travelClass),
					'originDestinationIds' => [ 0 ],
					'coverage' => 'MOST_SEGMENTS'
				]
			];
		}

		if(!empty($this->includedAirlineCodes)) {
			if (!isset($flightFilters['carrierRestrictions'])) {
				$carrierRestrictions = $flightFilters['carrierRestrictions'];
				$carrierRestrictions['includedCarrierCodes'] = $this->includedAirlineCodes;
				$flightFilters['carrierRestrictions'] = $carrierRestrictions;
			} else {
				$flightFilters['carrierRestrictions'] = [
					'includedCarrierCodes' => $this->includedAirlineCodes
				];
			}
		}

		if (!empty($this->excludedAirlineCodes)) {
			if (!isset($flightFilters['carrierRestrictions'])) {
				$carrierRestrictions = $flightFilters['carrierRestrictions'];
				$carrierRestrictions['excludedCarrierCodes'] = $this->excludedAirlineCodes;
				$flightFilters['carrierRestrictions'] = $carrierRestrictions;
			} else {
				$flightFilters['carrierRestrictions'] = [
					'excludedCarrierCodes' => $this->excludedAirlineCodes
				];
			}
		}

		if ($this->nonStop) {
			$flightFilters['connectionRestriction'] = [
				'maxNumberOfConnections' => 0
			];
		}
		$searchCriteria['flightFilters'] = $flightFilters;

		$params->searchCriteria = $searchCriteria;

		return json_encode($params);
	}

}
