<?php
/**
 * This file contains a class to represent a ticketing agreement in the Amadeus API.
 *
 * Class TicketingAgreement
 * @namespace Amadeus\Resources\Client
 */

namespace Amadeus\Resources\Client;

const CONFIRM = 'CONFIRM';
const DELAY_TO_CANCEL = 'DELAY_TO_CANCEL';
const DELAY_TO_QUEUE = 'DELAY_TO_QUEUE';

/**
 * Class TicketingAgreement
 * @package Amadeus\Resources\Client
 */
class TicketingAgreement
{
    private string $option;
    private string $delay;

    /**
     * TicketingAgreement constructor.
     */
    public function __construct()
    {
        $this->option = CONFIRM;
        $this->delay = '';
    }

    /**
     * Set the option to confirm.
     *
     * @return TicketingAgreement
     */
    public function setOptionToConfirm(): TicketingAgreement
    {
        $this->option = CONFIRM;
        return $this;
    }

    /**
     * Set the option to delay to cancel.
     *
     * @return TicketingAgreement
     */
    public function setOptionToDelayToCancel(): TicketingAgreement
    {
        $this->option = DELAY_TO_CANCEL;
        return $this;
    }

    /**
     * Set the option to delay to queue.
     *
     * @return TicketingAgreement
     */
    public function setOptionToDelayToQueue(): TicketingAgreement
    {
        $this->option = DELAY_TO_QUEUE;
        return $this;
    }

    /**
     * Set the delay.
     *
     * @param string $delay
     * @return TicketingAgreement
     */
    public function setDelay(string $delay): TicketingAgreement
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'option' => $this->option,
            'delay' => $this->delay
        ];
    }
}
