<?php

declare(strict_types=1);

namespace Amadeus\Tests;

use Amadeus\Airport;
use Amadeus\Amadeus;
use Amadeus\AmadeusBuilder;
use Amadeus\Booking;
use Amadeus\Client\AccessToken;
use Amadeus\Client\BasicHTTPClient;
use Amadeus\Configuration;
use Amadeus\DutyOfCare;
use Amadeus\DutyOfCare\Diseases;
use Amadeus\EReputation;
use Amadeus\ReferenceData;
use Amadeus\ReferenceData\Airlines;
use Amadeus\ReferenceData\Location;
use Amadeus\ReferenceData\Locations;
use Amadeus\ReferenceData\Locations\Airports;
use Amadeus\ReferenceData\Locations\Hotel;
use Amadeus\ReferenceData\Locations\Hotels;
use Amadeus\ReferenceData\Locations\Hotels\ByCity;
use Amadeus\ReferenceData\Locations\Hotels\ByGeocode;
use Amadeus\ReferenceData\Locations\Hotels\ByHotels;
use Amadeus\ReferenceData\RecommendedLocations;
use Amadeus\Resources\Resource;
use Amadeus\Schedule;
use Amadeus\Schedule\Flights;
use Amadeus\Shopping;
use Amadeus\Shopping\Activities;
use Amadeus\Shopping\Activities\BySquare;
use Amadeus\Shopping\Activity;
use Amadeus\Shopping\Availability;
use Amadeus\Shopping\Availability\FlightAvailabilities;
use Amadeus\Shopping\FlightDates;
use Amadeus\Shopping\FlightDestinations;
use Amadeus\Shopping\FlightOffers;
use Amadeus\Shopping\FlightOffers\Prediction;
use Amadeus\Shopping\FlightOffers\Pricing;
use Amadeus\Shopping\HotelOffer;
use Amadeus\Shopping\HotelOffers;
use Amadeus\Travel;
use Amadeus\Travel\Predictions;
use Amadeus\Travel\Predictions\FlightDelay;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Class NamespaceTest
 * @package Amadeus\Tests
 */
#[CoversClass(AccessToken::class)]
#[CoversClass(Activities::class)]
#[CoversClass(Activity::class)]
#[CoversClass(Airlines::class)]
#[CoversClass(Airport::class)]
#[CoversClass(Airport\DirectDestinations::class)]
#[CoversClass(Airport\Predictions::class)]
#[CoversClass(Airport\Predictions\OnTime::class)]
#[CoversClass(Airports::class)]
#[CoversClass(Amadeus::class)]
#[CoversClass(AmadeusBuilder::class)]
#[CoversClass(Availability::class)]
#[CoversClass(BasicHTTPClient::class)]
#[CoversClass(Booking::class)]
#[CoversClass(Booking\FlightOrders::class)]
#[CoversClass(Booking\HotelBookings::class)]
#[CoversClass(ByCity::class)]
#[CoversClass(ByGeocode::class)]
#[CoversClass(ByHotels::class)]
#[CoversClass(BySquare::class)]
#[CoversClass(Configuration::class)]
#[CoversClass(Diseases::class)]
#[CoversClass(Diseases\Covid19AreaReport::class)]
#[CoversClass(DutyOfCare::class)]
#[CoversClass(EReputation::class)]
#[CoversClass(FlightAvailabilities::class)]
#[CoversClass(FlightDates::class)]
#[CoversClass(FlightDelay::class)]
#[CoversClass(FlightDestinations::class)]
#[CoversClass(FlightOffers::class)]
#[CoversClass(Flights::class)]
#[CoversClass(Hotel::class)]
#[CoversClass(HotelOffer::class)]
#[CoversClass(HotelOffers::class)]
#[CoversClass(EReputation\HotelSentiments::class)]
#[CoversClass(Booking\HotelBookings::class)]
#[CoversClass(Hotels::class)]
#[CoversClass(Location::class)]
#[CoversClass(Locations::class)]
#[CoversClass(Prediction::class)]
#[CoversClass(Predictions::class)]
#[CoversClass(Pricing::class)]
#[CoversClass(RecommendedLocations::class)]
#[CoversClass(ReferenceData::class)]
#[CoversClass(Resource::class)]
#[CoversClass(Schedule::class)]
#[CoversClass(Shopping::class)]
#[CoversClass(Travel::class)]
final class NamespaceTest extends TestCase
{
    public function testAllNamespacesExist(): void
    {
        $amadeus = Amadeus::builder("id", "secret")->build();

        // Configuration
        $this->assertNotNull($amadeus->getConfiguration());

        // Configuration
        $this->assertNotNull($amadeus->getClient());

        // Airport
        $this->assertNotNull($amadeus->getAirport());
        $this->assertNotNull($amadeus->getAirport()->getDirectDestinations());
        $this->assertNotNull($amadeus->getAirport()->getPredictions());
        $this->assertNotNull($amadeus->getAirport()->getPredictions()->getOnTime());

        // Booking
        $this->assertNotNull($amadeus->getBooking());
        $this->assertNotNull($amadeus->getBooking()->getFlightOrders());
        $this->assertNotNull($amadeus->getBooking()->getHotelBookings());

        // Shopping
        $this->assertNotNull($amadeus->getShopping());
        $this->assertNotNull($amadeus->getShopping()->getActivities());
        $this->assertNotNull($amadeus->getShopping()->getActivities()->getBySquare());
        $this->assertNotNull($amadeus->getShopping()->getActivity("XXX"));
        $this->assertNotNull($amadeus->getShopping()->getAvailability());
        $this->assertNotNull($amadeus->getShopping()->getAvailability()->getFlightAvailabilities());
        $this->assertNotNull($amadeus->getShopping()->getFlightOffers());
        $this->assertNotNull($amadeus->getShopping()->getFlightOffers()->getPricing());
        $this->assertNotNull($amadeus->getShopping()->getFlightOffers()->getPrediction());
        $this->assertNotNull($amadeus->getShopping()->getHotelOffer("XXX"));
        $this->assertNotNull($amadeus->getShopping()->getHotelOffers());
        $this->assertNotNull($amadeus->getShopping()->getFlightDates());
        $this->assertNotNull($amadeus->getShopping()->getFlightDestinations());

        // ReferenceData
        $this->assertNotNull($amadeus->getReferenceData());
        $this->assertNotNull($amadeus->getReferenceData()->getLocation("XXX"));
        $this->assertNotNull($amadeus->getReferenceData()->getLocations());
        $this->assertNotNull($amadeus->getReferenceData()->getLocations()->getHotel());
        $this->assertNotNull($amadeus->getReferenceData()->getLocations()->getHotels());
        $this->assertNotNull($amadeus->getReferenceData()->getLocations()->getHotels()->getByCity());
        $this->assertNotNull($amadeus->getReferenceData()->getLocations()->getHotels()->getByGeocode());
        $this->assertNotNull($amadeus->getReferenceData()->getLocations()->getHotels()->getByHotels());
        $this->assertNotNull($amadeus->getReferenceData()->getLocations()->getAirports());
        $this->assertNotNull($amadeus->getReferenceData()->getAirlines());
        $this->assertNotNull($amadeus->getReferenceData()->getRecommendedLocations());

        // Schedule
        $this->assertNotNull($amadeus->getSchedule());
        $this->assertNotNull($amadeus->getSchedule()->getFlights());

        // Travel
        $this->assertNotNull($amadeus->getTravel());
        $this->assertNotNull($amadeus->getTravel()->getPredictions());
        $this->assertNotNull($amadeus->getTravel()->getPredictions()->getFlightDelay());

        // DutyOfCare
        $this->assertNotNull($amadeus->getDutyOfCare());
        $this->assertNotNull($amadeus->getDutyOfCare()->getDiseases());
        $this->assertNotNull($amadeus->getDutyOfCare()->getDiseases()->getCovid19AreaReport());

        // EReputation
        $this->assertNotNull($amadeus->getEReputation());
        $this->assertNotNull($amadeus->getEReputation()->getHotelSentiments());
    }
}
