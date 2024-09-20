<?php
/**
 * This class is modeled after the AirlineRemark in the Amadeus API.
 *
 * Class AirlineRemark
 * @package Amadeus\Resources\Client
 */
namespace Amadeus\Resources\Client;

const OTHER_SERVICE_INFORMATION  = 'OTHER_SERVICE_INFORMATION';
const KEYWORD                    = 'KEYWORD';
const OTHER_SERVICE              = 'OTHER_SERVICE';
const CLIENT_ID                  = 'CLIENT_ID';
const ADVANCED_TICKET_TIME_LIMIT = 'ADVANCED_TICKET_TIME_LIMIT';

/**
 * Class AirlineRemark
 * @package Amadeus\Resources\Client
 */
class AirlineRemark {

	private string $subType = OTHER_SERVICE_INFORMATION;
	private string $keyword;
	private string $airlineCode;
	private string $text;
	private array $travelerIds;
	private array $flightOfferIds;

	/**
	 * AirlineRemark constructor.
	 */
	public function __construct() {
		$this->keyword = '';
		$this->airlineCode = '';
		$this->text = '';
		$this->travelerIds = [];
		$this->flightOfferIds = [];
	}

	/**
	 * Set subType to OTHER_SERVICE_INFORMATION
	 *
	 * @return AirlineRemark
	 */
	public function setOtherServiceInformation(): AirlineRemark {
		$this->subType = OTHER_SERVICE_INFORMATION;
		return $this;
	}

	/**
	 * Set subType to KEYWORD
	 *
	 * @return AirlineRemark
	 */
	public function setKeyword(): AirlineRemark {
		$this->subType = KEYWORD;
		return $this;
	}

	/**
	 * Set subType to OTHER_SERVICE
	 *
	 * @return $this
	 */
	public function setOtherService(): AirlineRemark {
		$this->subType = OTHER_SERVICE;
		return $this;
	}

	/**
	 * Set subType to CLIENT_ID
	 *
	 * @return $this
	 */
	public function setClientId(): AirlineRemark {
		$this->subType = CLIENT_ID;
		return $this;
	}

	/**
	 * Set subType to ADVANCED_TICKET_TIME_LIMIT
	 *
	 * @return $this
	 */
	public function setAdvancedTicketTimeLimit(): AirlineRemark {
		$this->subType = ADVANCED_TICKET_TIME_LIMIT;
		return $this;
	}

	/**
	 * @param string $keyword
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setKeywordValue(string $keyword): AirlineRemark {

		if ($this->subType !== KEYWORD) {
			throw new \Exception('Keyword value can only be set for KEYWORD subType');
		}

		$this->keyword = $keyword;
		return $this;
	}

	/**
	 * @param string $airlineCode
	 *
	 * @return $this
	 */
	public function setAirlineCode(string $airlineCode): AirlineRemark {
		$this->airlineCode = $airlineCode;
		return $this;
	}

	/**
	 * @param string $text
	 *
	 * @return $this
	 */
	public function setText(string $text): AirlineRemark {
		$this->text = $text;
		return $this;
	}

	/**
	 * @param string $travelerId
	 *
	 * @return $this
	 */
	public function addTravelerId(string $travelerId): AirlineRemark {
		$this->travelerIds[] = $travelerId;
		return $this;
	}

	/**
	 * @param string $travelerId
	 *
	 * @return $this
	 */
	public function removeTravelerId(string $travelerId): AirlineRemark {
		$this->travelerIds = array_diff($this->travelerIds, [$travelerId]);
		return $this;
	}

	/**
	 * @param string $flightOfferId
	 *
	 * @return $this
	 */
	public function addFlightOfferId(string $flightOfferId): AirlineRemark {
		$this->flightOfferIds[] = $flightOfferId;
		return $this;
	}

	/**
	 * @param string $flightOfferId
	 *
	 * @return $this
	 */
	public function removeFlightOfferId(string $flightOfferId): AirlineRemark {
		$this->flightOfferIds = array_diff($this->flightOfferIds, [$flightOfferId]);
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return json_encode([
			'subType' => $this->subType,
			'keyword' => $this->keyword,
			'airlineCode' => $this->airlineCode,
			'text' => $this->text,
			'travelerIds' => $this->travelerIds,
			'flightOfferIds' => $this->flightOfferIds
		]);
	}
}