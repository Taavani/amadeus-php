<?php
/**
 * This file contains a class to represent a form of identification in the Amadeus API.
 *
 * @namespace Amadeus\Resources\Client
 * @package Amadeus\Resources\Client
 */

namespace Amadeus\Resources\Client;

const DRIVERS_LICENSE = 'DRIVERS_LICENSE';
const PASSPORT = 'PASSPORT';
const NATIONAL_IDENTITY_CARD = 'NATIONAL_IDENTITY_CARD';
const BOOKING_CONFIRMATION = 'BOOKING_CONFIRMATION';
const TICKET = 'TICKET';
const OTHER_ID = 'OTHER_ID';

/**
 * Class FormOfIdentification
 * @package Amadeus\Resources\Client
 */
class FormOfIdentification
{
	private string $identificationType;
	private string $carrierCode;
	private string $number;
	private array $travelerIds;
	private array $flightOfferIds;

	/**
	 * FormOfIdentification constructor.
	 */
	public function __construct() {
		$this->identificationType = DRIVERS_LICENSE;
		$this->carrierCode = '';
		$this->number = '';
		$this->travelerIds = [];
		$this->flightOfferIds = [];
	}

	/**
	 * Set the identification type to driver's license.
	 *
	 * @return FormOfIdentification
	 */
	public function setIdentificationTypeToDriversLicense(): FormOfIdentification {
		$this->identificationType = DRIVERS_LICENSE;
		return $this;
	}

	/**
	 * Set the identification type to passport.
	 *
	 * @return FormOfIdentification
	 */
	public function setIdentificationTypeToPassport(): FormOfIdentification {
		$this->identificationType = PASSPORT;
		return $this;
	}

	/**
	 * Set the identification type to national identity card.
	 *
	 * @return FormOfIdentification
	 */
	public function setIdentificationTypeToNationalIdentityCard(): FormOfIdentification {
		$this->identificationType = NATIONAL_IDENTITY_CARD;
		return $this;
	}

	/**
	 * Set the identification type to booking confirmation.
	 *
	 * @return FormOfIdentification
	 */
	public function setIdentificationTypeToBookingConfirmation(): FormOfIdentification {
		$this->identificationType = BOOKING_CONFIRMATION;
		return $this;
	}

	/**
	 * Set the identification type to ticket.
	 *
	 * @return FormOfIdentification
	 */
	public function setIdentificationTypeToTicket(): FormOfIdentification {
		$this->identificationType = TICKET;
		return $this;
	}

	/**
	 * Set the identification type to other ID.
	 *
	 * @return FormOfIdentification
	 */
	public function setIdentificationTypeToOtherId(): FormOfIdentification {
		$this->identificationType = OTHER_ID;
		return $this;
	}

	/**
	 * Set the carrier code.
	 *
	 * @param string $carrierCode
	 * @return FormOfIdentification
	 */
	public function setCarrierCode(string $carrierCode): FormOfIdentification {
		$this->carrierCode = $carrierCode;
		return $this;
	}

	/**
	 * Set the number.
	 *
	 * @param string $number
	 * @return FormOfIdentification
	 */
	public function setNumber(string $number): FormOfIdentification {
		$this->number = $number;
		return $this;
	}

	/**
	 * Add a traveler ID.
	 *
	 * @param string $travelerId
	 * @return FormOfIdentification
	 */
	public function addTravelerId(string $travelerId): FormOfIdentification {
		$this->travelerIds[] = $travelerId;
		return $this;
	}

	/**
	 * Remove a traveler ID.
	 *
	 * @param string $travelerId
	 *
	 * @return FormOfIdentification
	 */
	public function removeTravelerId(string $travelerId): FormOfIdentification {
		$key = array_search($travelerId, $this->travelerIds);
		if ($key !== false) {
			unset($this->travelerIds[$key]);
		}
		return $this;
	}

	/**
	 * Add a flight offer ID.
	 *
	 * @param string $flightOfferId
	 * @return FormOfIdentification
	 */
	public function addFlightOfferId(string $flightOfferId): FormOfIdentification {
		$this->flightOfferIds[] = $flightOfferId;
		return $this;
	}

	/**
	 * Remove a flight offer ID.
	 *
	 * @param string $flightOfferId
	 * @return FormOfIdentification
	 */
	public function removeFlightOfferId(string $flightOfferId): FormOfIdentification {
		$key = array_search($flightOfferId, $this->flightOfferIds);
		if ($key !== false) {
			unset($this->flightOfferIds[$key]);
		}
		return $this;
	}

	/**
	 * Convert the object to an array.
	 *
	 * @return array
	 */
	public function __toArray(): array {
		return [
			'identificationType' => $this->identificationType,
			'carrierCode' => $this->carrierCode,
			'number' => $this->number,
			'travelerIds' => $this->travelerIds,
			'flightOfferIds' => $this->flightOfferIds
		];
	}
}