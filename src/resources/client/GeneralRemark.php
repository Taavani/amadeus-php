<?php
/**
 * This class is modeled after the GeneralRemark in the Amadeus API.
 *
 * Class GeneralRemark
 * @package Amadeus\Resources\Client
 */
namespace Amadeus\Resources\Client;

const GENERAL_MISCELLANEOUS = 'GENERAL_MISCELLANEOUS';
const CONFIDENTIAL = 'CONFIDENTIAL';
const INVOICE = 'INVOICE';
const QUALITY_CONTROL = 'QUALITY_CONTROL';
const BACKOFFICE = 'BACKOFFICE';
const FULFILLMENT = 'FULFILLMENT';
const ITINERARY = 'ITINERARY';
const TICKETING_MISCELLANEOUS = 'TICKETING_MISCELLANEOUS';
const TOUR_CODE = 'TOUR_CODE';

/**
 * This class is modeled after the GeneralRemark in the Amadeus API.
 *
 * Class GeneralRemark
 * @package Amadeus\Resources\Client
 */
class GeneralRemark {

	private string $subType = GENERAL_MISCELLANEOUS;
	private string $category;
	private string $text;
	private array $travelerIds;
	private array $flightOfferIds;

	/**
	 * GeneralRemark constructor.
	 */
	public function __construct() {

		$this->category = '';
		$this->text = '';
		$this->travelerIds = [];
		$this->flightOfferIds = [];
	}

	/**
	 * Set subType to GENERAL_MISCELLANEOUS
	 *
	 * @return GeneralRemark
	 */
	public function setGeneralMiscellaneous(): GeneralRemark {
		$this->subType = GENERAL_MISCELLANEOUS;
		return $this;
	}

	/**
	 * Set subType to CONFIDENTIAL
	 *
	 * @return GeneralRemark
	 */
	public function setConfidential(): GeneralRemark {
		$this->subType = CONFIDENTIAL;
		return $this;
	}

	/**
	 * Set subType to INVOICE
	 *
	 * @return GeneralRemark
	 */
	public function setInvoice(): GeneralRemark {
		$this->subType = INVOICE;
		return $this;
	}

	/**
	 * Set subType to QUALITY_CONTROL
	 *
	 * @return GeneralRemark
	 */
	public function setQualityControl(): GeneralRemark {
		$this->subType = QUALITY_CONTROL;
		return $this;
	}

	/**
	 * Set subType to BACKOFFICE
	 *
	 * @return GeneralRemark
	 */
	public function setBackoffice(): GeneralRemark {
		$this->subType = BACKOFFICE;
		return $this;
	}

	/**
	 * Set subType to FULFILLMENT
	 *
	 * @return GeneralRemark
	 */
	public function setFulfillment(): GeneralRemark {
		$this->subType = FULFILLMENT;
		return $this;
	}

	/**
	 * Set subType to ITINERARY
	 *
	 * @return GeneralRemark
	 */
	public function setItinerary(): GeneralRemark {
		$this->subType = ITINERARY;
		return $this;
	}

	/**
	 * Set subType to TICKETING_MISCELLANEOUS
	 *
	 * @return GeneralRemark
	 */
	public function setTicketingMiscellaneous(): GeneralRemark {
		$this->subType = TICKETING_MISCELLANEOUS;
		return $this;
	}

	/**
	 * Set subType to TOUR_CODE
	 *
	 * @return GeneralRemark
	 */
	public function setTourCode(): GeneralRemark {
		$this->subType = TOUR_CODE;
		return $this;
	}

	/**
	 * @param string $category
	 *
	 * @return GeneralRemark
	 */
	public function setCategory(string $category): GeneralRemark {
		$this->category = $category;
		return $this;
	}

	/**
	 * @param string $text
	 *
	 * @return GeneralRemark
	 */
	public function setText(string $text): GeneralRemark {
		$this->text = $text;
		return $this;
	}

	/**
	 * @param string $travelerId
	 *
	 * @return GeneralRemark
	 */
	public function addTravelerId(string $travelerId): GeneralRemark {
		$this->travelerIds[] = $travelerId;
		return $this;
	}

	/**
	 * @param string $travelerId
	 *
	 * @return GeneralRemark
	 */
	public function removeTravelerId(string $travelerId): GeneralRemark {
		$this->travelerIds = array_diff($this->travelerIds, [$travelerId]);
		return $this;
	}

	/**
	 * @param string $flightOfferId
	 *
	 * @return GeneralRemark
	 */
	public function addFlightOfferId(string $flightOfferId): GeneralRemark {
		$this->flightOfferIds[] = $flightOfferId;
		return $this;
	}

	/**
	 * @param string $flightOfferId
	 *
	 * @return GeneralRemark
	 */
	public function removeFlightOfferId(string $flightOfferId): GeneralRemark {
		$this->flightOfferIds = array_diff($this->flightOfferIds, [$flightOfferId]);
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		$data = [];
		$data['subType'] = $this->subType;

		if ($this->category) {
			$data['category'] = $this->category;
		}

		if ($this->text) {
			$data['text'] = $this->text;
		}

		if (count($this->travelerIds) > 0) {
			$data['travelerIds'] = $this->travelerIds;
		}

		if (count($this->flightOfferIds) > 0) {
			$data['flightOfferIds'] = $this->flightOfferIds;
		}

		return json_encode($data);
	}

}