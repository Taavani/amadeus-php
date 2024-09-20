<?php
/**
 * This class is modeled after the Remarks in the Amadeus API.
 *
 * Class Remarks
 * @package Amadeus\Resources\Client
 *
 */
namespace Amadeus\Resources\Client;

/**
 * Class Remarks
 * @package Amadeus\Resources\Client
 */
class Remarks
{
    private array $general;

    private array $airline;

	/**
	 * Remarks constructor.
	 */
	public function __construct()
	{
		$this->general = [];
		$this->airline = [];
	}

	/**
	 * @param GeneralRemark $generalRemark
	 *
	 * @return Remarks
	 */
	public function addGeneralRemark(GeneralRemark $generalRemark): Remarks
	{
		$this->general[] = $generalRemark;
		return $this;
	}

	/**
	 * @param GeneralRemark $generalRemark
	 *
	 * @return Remarks
	 */
	public function removeGeneralRemark(GeneralRemark $generalRemark): Remarks
	{
		$key = array_search($generalRemark, $this->general);
		if ($key !== false) {
			unset($this->general[$key]);
		}
		return $this;
	}

	/**
	 * @param AirlineRemark $airlineRemark
	 *
	 * @return Remarks
	 */
	public function addAirlineRemark(AirlineRemark $airlineRemark): Remarks
	{
		$this->airline[] = $airlineRemark;
		return $this;
	}

	/**
	 * @param AirlineRemark $airlineRemark
	 *
	 * @return Remarks
	 */
	public function removeAirlineRemark(AirlineRemark $airlineRemark): Remarks
	{
		$key = array_search($airlineRemark, $this->airline);
		if ($key !== false) {
			unset($this->airline[$key]);
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function __toArray(): array
	{
		$gRemarks = array_map(function ($generalRemark) {
			return json_decode($generalRemark->__toString(), true);
		}, $this->general);

		$aRemarks = array_map(function ($airlineRemark) {
			return json_decode($airlineRemark->__toString(), true);
		}, $this->airline);

		return [
			'general' => $gRemarks,
			'airline' => $aRemarks
		];
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		$gRemarks = array_map(function ($generalRemark) {
			return json_decode($generalRemark->__toString(), true);
		}, $this->general);

		$aRemarks = array_map(function ($airlineRemark) {
			return json_decode($airlineRemark->__toString(), true);
		}, $this->airline);

		return json_encode([
			'general' => $gRemarks,
			'airline' => $aRemarks
		]);
	}
}