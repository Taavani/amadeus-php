<?php

namespace Amadeus\Resources\Client;

const DRIVERS_LICENSE = 'DRIVERS_LICENSE';
const PASSPORT = 'PASSPORT';
const NATIONAL_IDENTITY_CARD = 'NATIONAL_IDENTITY_CARD';
const BOOKING_CONFIRMATION = 'BOOKING_CONFIRMATION';
const TICKET = 'TICKET';
const OTHER_ID = 'OTHER_ID';

class FormOfIdentification
{
	private string $identificationType;
	private string $carrierCode;
	private string $number;
	private array $travelerIds;
	private array $flightOfferIds;

	public function __construct() {
		$this->identificationType = DRIVERS_LICENSE;
		$this->carrierCode = '';
		$this->number = '';
		$this->travelerIds = [];
		$this->flightOfferIds = [];
	}

	public function setIdentificationTypeToDriversLicense(): FormOfIdentification {
		$this->identificationType = DRIVERS_LICENSE;
		return $this;
	}

	public function setIdentificationTypeToPassport(): FormOfIdentification {
		$this->identificationType = PASSPORT;
		return $this;
	}

	public function setIdentificationTypeToNationalIdentityCard(): FormOfIdentification {
		$this->identificationType = NATIONAL_IDENTITY_CARD;
		return $this;
	}

	public function setIdentificationTypeToBookingConfirmation(): FormOfIdentification {
		$this->identificationType = BOOKING_CONFIRMATION;
		return $this;
	}

	public function setIdentificationTypeToTicket(): FormOfIdentification {
		$this->identificationType = TICKET;
		return $this;
	}

	public function setIdentificationTypeToOtherId(): FormOfIdentification {
		$this->identificationType = OTHER_ID;
		return $this;
	}

	public function setCarrierCode(string $carrierCode): FormOfIdentification {
		$this->carrierCode = $carrierCode;
		return $this;
	}

	public function setNumber(string $number): FormOfIdentification {
		$this->number = $number;
		return $this;
	}

	public function addTravelerId(string $travelerId): FormOfIdentification {
		$this->travelerIds[] = $travelerId;
		return $this;
	}

	public function removeTravelerId(string $travelerId): FormOfIdentification {
		$key = array_search($travelerId, $this->travelerIds);
		if ($key !== false) {
			unset($this->travelerIds[$key]);
		}
		return $this;
	}

	public function addFlightOfferId(string $flightOfferId): FormOfIdentification {
		$this->flightOfferIds[] = $flightOfferId;
		return $this;
	}

	public function removeFlightOfferId(string $flightOfferId): FormOfIdentification {
		$key = array_search($flightOfferId, $this->flightOfferIds);
		if ($key !== false) {
			unset($this->flightOfferIds[$key]);
		}
		return $this;
	}

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