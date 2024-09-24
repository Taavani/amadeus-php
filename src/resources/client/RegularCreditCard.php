<?php
/**
 * This file contains a class to represent a regular credit card.
 *
 * class RegularCreditCard
 * @namespace Amadeus\Resources\Client
 */
namespace Amadeus\Resources\Client;

const VISA = 'VISA';
const VISA_ELECTRON = 'VISA_ELECTRON';
const VISA_DEBIT = 'VISA_DEBIT';
const MASTERCARD = 'MASTERCARD';
const MASTERCARD_DEBIT = 'MASTERCARD_DEBIT';
const AMERICAN_EXPRESS = 'AMERICAN_EXPRESS';
const DINERS = 'DINERS';
const MAESTRO = 'MAESTRO';
const EASYPAY = 'EASYPAY';

/**
 * Class RegularCreditCard
 */
class RegularCreditCard extends FormOfPayment {

	private string $brand;
	private string $holder;
	private string $number;
	private string $expiryDate;
	private string $securityCode;
	private array $flightOfferIds;

	/**
	 * RegularCreditCard constructor.
	 */
	public function __construct()
	{
		$this->brand = VISA;
		$this->holder = '';
	}

	/**
	 * This function can be used to determine the brand of the credit card. The valid brands are:
	 *
	 * VISA, VISA_ELECTRON, VISA_DEBIT, MASTERCARD, MASTERCARD_DEBIT, AMERICAN_EXPRESS, DINERS, MAESTRO, EASYPAY.
	 *
	 * @param string $brand
	 *
	 * @return $this
	 */
	public function determineBrand(string $brand): RegularCreditCard
	{
		if (!in_array($brand, [VISA, VISA_ELECTRON, VISA_DEBIT, MASTERCARD, MASTERCARD_DEBIT, AMERICAN_EXPRESS, DINERS, MAESTRO, EASYPAY])) {
			throw new \InvalidArgumentException('Invalid credit card brand.');
		}

		$this->brand = $brand;
		return $this;
	}

	/**
	 * Set the brand of the credit card to VISA.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsVisa(): RegularCreditCard
	{
		$this->brand = VISA;
		return $this;
	}

	/**
	 * Set the brand of the credit card to VISA ELECTRON.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsVisaElectron(): RegularCreditCard
	{
		$this->brand = VISA_ELECTRON;
		return $this;
	}

	/**
	 * Set the brand of the credit card to VISA DEBIT.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsVisaDebit(): RegularCreditCard
	{
		$this->brand = VISA_DEBIT;
		return $this;
	}

	/**
	 * Set the brand of the credit card to MASTERCARD.
	 *
	 * @return $this
	 */
	public function setAsMastercard(): RegularCreditCard
	{
		$this->brand = MASTERCARD;
		return $this;
	}

	/**
	 * Set the brand of the credit card to MASTERCARD DEBIT.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsMastercardDebit(): RegularCreditCard
	{
		$this->brand = MASTERCARD_DEBIT;
		return $this;
	}

	/**
	 * Set the brand of the credit card to AMERICAN EXPRESS.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsAmericanExpress(): RegularCreditCard
	{
		$this->brand = AMERICAN_EXPRESS;
		return $this;
	}

	/**
	 * Set the brand of the credit card to DINERS.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsDiners(): RegularCreditCard
	{
		$this->brand = DINERS;
		return $this;
	}

	/**
	 * Set the brand of the credit card to MAESTRO.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsMaestro(): RegularCreditCard
	{
		$this->brand = MAESTRO;
		return $this;
	}

	/**
	 * Set the brand of the credit card to EASYPAY.
	 *
	 * @return RegularCreditCard
	 */
	public function setAsEasypay(): RegularCreditCard
	{
		$this->brand = EASYPAY;
		return $this;
	}

	/**
	 * Cardholder name as is spelled on the card.
	 *
	 * example: MR DUPON DAMIEN
	 *
	 * @param string $holder
	 *
	 * @return $this
	 */
	public function setHolder(string $holder): RegularCreditCard
	{
		$this->holder = $holder;
		return $this;
	}

	/**
	 * Card number as is printed on the card.
	 *
	 * example: 4444333322221111
	 * pattern: [a-zA-Z0-9]{1,35}
	 *
	 * @param string $number
	 *
	 * @return $this
	 */
	public function setNumber(string $number): RegularCreditCard
	{
		if (!preg_match('/^[a-zA-Z0-9]{1,35}$/', $number)) {
			throw new \InvalidArgumentException('Invalid card number format.');
		}

		$this->number = $number;
		return $this;
	}

	/**
	 * Expiry date of the card.
	 *
	 * example: 2021-08
	 * pattern: credit card expiration date following ISO 8601 (YYYY-MM format, e.g. 2012-08)
     *
	 * @param string $expiryDate
	 *
	 * @return $this
	 */
	public function setExpiryDate(string $expiryDate): RegularCreditCard
	{
		$this->expiryDate = $expiryDate;
		return $this;
	}

	/**
	 * Security code as is printed on the card.
	 *
	 * example: 737
	 * pattern: [0-9]{3,4}
	 *
	 * @param string $securityCode
	 *
	 * @return $this
	 */
	public function setSecurityCode(string $securityCode): RegularCreditCard
	{
		if (!preg_match('/^[0-9]{3,4}$/', $securityCode)) {
			throw new \InvalidArgumentException('Invalid security code format.');
		}

		$this->securityCode = $securityCode;
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function addFlightOfferIds(string $flightOfferId): RegularCreditCard
	{
		$this->flightOfferIds[] = $flightOfferId;
		return $this;
	}

	/**
	 *
	 * @return $this
	 */
	public function removeFlightOfferIds(string $flightOfferId): RegularCreditCard
	{
		$key = array_search($flightOfferId, $this->flightOfferIds);
		if ($key !== false) {
			unset($this->flightOfferIds[$key]);
		}
		return $this;
	}

	/**
	 * @return array[]
	 */
	public function __toArray(): array
	{
		return [
			'creditCard' => [
				'brand' => $this->brand,
				'holder' => $this->holder,
				'number' => $this->number,
				'expiryDate' => $this->expiryDate,
				'securityCode' => $this->securityCode,
				'flightOfferIds' => $this->flightOfferIds
			]
		];
	}
}