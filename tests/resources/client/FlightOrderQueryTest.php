<?php

namespace Amadeus\Tests\resources\client;

use Amadeus\Resources\Client\AirlineRemark;
use Amadeus\Resources\Client\AutomatedProcess;
use Amadeus\Resources\client\FlightOrderQuery;
use Amadeus\Resources\Client\FormOfIdentification;
use Amadeus\Resources\Client\GeneralRemark;
use Amadeus\Resources\Client\Queue;
use Amadeus\Resources\Client\Remarks;
use Amadeus\Resources\Client\TicketingAgreement;
use Amadeus\Resources\FlightOffer;
use Amadeus\Resources\FlightOrder;
use Amadeus\Resources\Resource;
use Amadeus\Resources\TravelerAddress;
use Amadeus\Resources\TravelerContact;
use Amadeus\Resources\TravelerName;
use Amadeus\Resources\TravelerPhone;
use Amadeus\Tests\PHPUnitUtil;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Class FlightOrderQueryTest
 * @package Amadeus\Tests\resources\client
 */
#[
	CoversClass(FlightOrderQuery::class)
]
class FlightOrderQueryTest extends TestCase
{
    public function test_basic_flight_order_query()
    {
		$officeId = 'GOHS12101';

        $flightOrderQuery = new FlightOrderQuery();

        $this->assertEquals('flight-order', $flightOrderQuery->getType());

		$flightOrderQuery->setQueuingOfficeId($officeId);
		$this->assertEquals($officeId , $flightOrderQuery->getQueuingOfficeId());

		$flightOrderQuery->setOwnerOfficeId($officeId);
		$this->assertEquals($officeId , $flightOrderQuery->getOwnerOfficeId());

	    /**
	     * Test Flight Offer setter and getter
	     */
		$flightOffersBody = PHPUnitUtil::readFile(
		    PHPUnitUtil::RESOURCE_PATH_ROOT . "flight_offers_get_response_ok.json"
	    );
		// Map to FlightOffers
		$flightOffers = Resource::toResourceArray(json_decode($flightOffersBody, false)->data, FlightOffer::class);
		// Add first flight offer
		$flightOrderQuery->addFlightOffer($flightOffers[0]);

	    /**
	     * Test Traveler setter and getter
	     * Get the Traveler object from the FlightOrder
	     */
		$flightOrderBody = PHPUnitUtil::readFile(
			PHPUnitUtil::RESOURCE_PATH_ROOT . "flight_orders_post_request_ok.json"
		);
		// Map to FlightOrder
		$flightOrder = Resource::toResourceObject(json_decode($flightOrderBody, false)->data, FlightOrder::class);

		// Add first traveler
		$flightOrderQuery->addTraveler($flightOrder->getTravelers()[0]);

		//
	    $generalRemark = new GeneralRemark();
		$generalRemark->setGeneralMiscellaneous();
		$generalRemark->setText('This is a test remark');
		$generalRemark->setCategory('General');
		$generalRemark->addTravelerId('1');
		$generalRemark->addTravelerId('2');
		$generalRemark->addFlightOfferId('1');

		$airlineRemark = new AirlineRemark();
		$airlineRemark->setAirlineCode('AA');
		$airlineRemark->setText('This is an airline remark');
	    $generalRemark->addTravelerId('1');
		$airlineRemark->addFlightOfferId('1');

	    $remarks = new Remarks();
		$remarks->addGeneralRemark($generalRemark);
		$remarks->addAirlineRemark($airlineRemark);
		$flightOrderQuery->setRemarks($remarks);

		$ticketingAgreement = new TicketingAgreement();
		$ticketingAgreement->setOptionToConfirm();
		$flightOrderQuery->setTicketingAgreement($ticketingAgreement);

		$automatedProcess = new AutomatedProcess();
		$automatedProcess->setCodeToImmediate();
		$queue = new Queue();
		$queue->setNumber('1');
		$queue->setCategory('A');
		$automatedProcess->setQueue($queue);
		$automatedProcess->setText('This is an automated process');
		$flightOrderQuery->addAutomatedProcess($automatedProcess);

		$traveler = new TravelerContact();

		$name = new TravelerName();
		$name->__set('firstName', 'John');
		$name->__set('lastName', 'Doe');
		$traveler->__set('addresseeName', $name);

		$traveler->__set('companyName', 'Acme Inc');
		$traveler->__set('purpose', 'INVOICE');

		$phone = new TravelerPhone();
		$phone->__set('number', '1234567890');
		$phone->__set('purpose', 'MOBILE');
		$traveler->__set('phones', [$phone]);

		$traveler->__set('emailAddress', 'blob@blob.gl');

		$address = new TravelerAddress();
		$address->__set('lines', ['123 Main St']);
		$address->__set('cityName', 'Anytown');
		$address->__set('stateName', 'NY');
		$address->__set('postalCode', '12345');
		$address->__set('countryCode', 'US');
		$traveler->__set('address', $address);

		$traveler->__set('language', 'EN');
		$flightOrderQuery->addContact($traveler);

		$formOfIdentification = new FormOfIdentification();
		$formOfIdentification->setIdentificationTypeToTicket();
		$formOfIdentification->setCarrierCode('AA');
		$formOfIdentification->setNumber('1234567890');
		$formOfIdentification->addTravelerId('1');
		$formOfIdentification->addFlightOfferId('1');
		$flightOrderQuery->addFormOfIdentification($formOfIdentification);

		$this->assertEquals($flightOrderQuery->getType(), 'flight-order');
    }
}
