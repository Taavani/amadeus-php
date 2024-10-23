<?php

namespace Amadeus\Resources\Client;

use Amadeus\Resources\FlightOffer;
use Amadeus\Resources\TravelerContact;
use Amadeus\Resources\TravelerElement;

/**
 * This class is modeled after the FlightOrderQuery in the Amadeus API.
 *
 * Class FlightOrderQuery
 * @package Amadeus\Resources\Client
 */
class FlightOrderQuery
{
    private string $type = 'flight-order';

    private ?string $queuingOfficeId;

    private ?string $ownerOfficeId;

    private array $flightOffers;

    private array $travelers;

    private ?Remarks $remarks;

	private ?FormOfPayment $formOfPayment;

    private ?TicketingAgreement $ticketingAgreement;

    private array $automatedProcess;

    private array $contacts;

    private array $formOfIdentifications;

    /**
     * FlightOrderQuery constructor.
     */
    public function __construct()
    {
        $this->queuingOfficeId = null;
        $this->ownerOfficeId = null;
        $this->flightOffers = [];
        $this->travelers = [];
        $this->remarks = null;
		$this->formOfPayment = null;
        $this->ticketingAgreement = null;
        $this->automatedProcess = [];
        $this->contacts = [];
        $this->formOfIdentifications = [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

	/**
	 * The office ID where to queue the order
	 *
	 * @return string|null
	 */
	public function getQueuingOfficeId(): ?string
	{
		return $this->queuingOfficeId;
	}

	/**
	 * Office ID where to queue the order
	 *
	 * @param string $queuingOfficeId
	 * @return $this
	 */
    public function setQueuingOfficeId(string $queuingOfficeId): static
    {
        $this->queuingOfficeId = $queuingOfficeId;
        return $this;
    }

	/**
	 * The office ID where to transfer the ownership of the order
	 *
	 * @return string|null
	 */
	public function getOwnerOfficeId(): ?string
	{
		return $this->ownerOfficeId;
	}

    /**
     * Office ID where will be transferred the ownership of the order
     *
     * @param string $ownerOfficeId
     * @return $this
     */
    public function setOwnerOfficeId(string $ownerOfficeId): static
    {
        $this->ownerOfficeId = $ownerOfficeId;
        return $this;
    }

    /**
     * @param FlightOffer $flightOffer
     * @return $this
     */
    public function addFlightOffer(FlightOffer $flightOffer): static
    {
        $this->flightOffers[] = $flightOffer;
        return $this;
    }

    /**
     * @param FlightOffer $flightOffer
     * @return $this
     */
    public function removeFlightOffer(FlightOffer $flightOffer): static
    {
        $this->flightOffers = array_diff($this->flightOffers, [$flightOffer]);
        return $this;
    }

    /**
     * @param TravelerElement $traveler
     * @return $this
     */
    public function addTraveler(TravelerElement $traveler): static
    {
        $this->travelers[] = $traveler;
        return $this;
    }

    /**
     * @param TravelerElement $traveler
     * @return $this
     */
    public function removeTraveler(TravelerElement $traveler): static
    {
        $this->travelers = array_diff($this->travelers, [$traveler]);
        return $this;
    }

    /**
     * @param Remarks $remarks
     * @return $this
     */
    public function setRemarks(Remarks $remarks): static
    {
        $this->remarks = $remarks;
        return $this;
    }

	/**
	 * @param FormOfPayment $formOfPayment
	 * @return $this
	 */
	public function setFormOfPayment(FormOfPayment $formOfPayment): static
	{
		$this->formOfPayment = $formOfPayment;
		return $this;
	}

    /**
     * @param TicketingAgreement $ticketingAgreement
     * @return $this
     */
    public function setTicketingAgreement(TicketingAgreement $ticketingAgreement): static
    {
        $this->ticketingAgreement = $ticketingAgreement;
        return $this;
    }

    /**
     * @param AutomatedProcess $automatedProcess
     * @return $this
     */
    public function addAutomatedProcess(AutomatedProcess $automatedProcess): static
    {
        $this->automatedProcess[] = $automatedProcess;
        return $this;
    }

    /**
     * @param AutomatedProcess $automatedProcess
     * @return $this
     */
    public function removeAutomatedProcess(AutomatedProcess $automatedProcess): static
    {
        $this->automatedProcess = array_diff($this->automatedProcess, [$automatedProcess]);
        return $this;
    }

    /**
     * @param TravelerContact $contact
     * @return $this
     */
    public function addContact(TravelerContact $contact): static
    {
        $this->contacts[] = $contact;
        return $this;
    }

    /**
     * @param TravelerContact $contact
     * @return $this
     */
    public function removeContact(TravelerContact $contact): static
    {
        $this->contacts = array_diff($this->contacts, [$contact]);
        return $this;
    }

    /**
     * @param FormOfIdentification $formOfIdentification
     * @return $this
     */
    public function addFormOfIdentification(FormOfIdentification $formOfIdentification): static
    {
        $this->formOfIdentifications[] = $formOfIdentification;
        return $this;
    }

    /**
     * @param FormOfIdentification $formOfIdentification
     * @return $this
     */
    public function removeFormOfIdentification(FormOfIdentification $formOfIdentification): static
    {
        $this->formOfIdentifications = array_diff($this->formOfIdentifications, [$formOfIdentification]);
        return $this;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        $data = [];
        $data['type'] = $this->type;

        if ($this->queuingOfficeId) {
            $data['queuingOfficeId'] = $this->queuingOfficeId;
        }

        if ($this->ownerOfficeId) {
            $data['ownerOfficeId'] = $this->ownerOfficeId;
        }

        if (count($this->flightOffers) > 0) {
            $data['flightOffers'] = array_map(
				function (FlightOffer $flightOffer) {
					return json_decode($flightOffer->__toString());
				},
	            $this->flightOffers
            );
        }

        if (count($this->travelers) > 0) {
            $data['travelers'] = array_map(
				function (TravelerElement $traveler) {
	                return json_decode($traveler->__toString());
                },
	            $this->travelers
            );
        }

		if ($this->remarks) {
            $data['remarks'] = $this->remarks->__toArray();
        }

		if ($this->formOfPayment) {
			$data['formOfPayments'][] = $this->formOfPayment->__toArray();
		}

        if ($this->ticketingAgreement) {
            $data['ticketingAgreement'] = $this->ticketingAgreement->__toArray();
        }

        if (count($this->automatedProcess) > 0) {
            $data['automatedProcess'] = array_map(function ($process) {
				return $process->__toArray();
            }, $this->automatedProcess);
        }

		if (count($this->contacts) > 0) {
			$data['contacts'] = array_map(function ($contact) {
				return $contact->__toArray();
			}, $this->contacts);
		}

		if (count($this->formOfIdentifications) > 0) {
			$data['formOfIdentifications'] = array_map(function ($formOfIdentification) {
				return $formOfIdentification->__toArray();
			}, $this->formOfIdentifications);
		}


        return [
            'data' => $data
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->__toArray(), JSON_PRETTY_PRINT);
    }
}