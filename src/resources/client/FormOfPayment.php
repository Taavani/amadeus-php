<?php
/**
 * This file contains a class to represent a form of payment.
 *
 * class FormOfPayment
 * @namespace Amadeus\Resources\Client
 */

namespace Amadeus\Resources\Client;

/**
 *  This file contains a class to represent a form of payment.
 */
abstract class FormOfPayment
{
    abstract public function __toArray(): array;
}
