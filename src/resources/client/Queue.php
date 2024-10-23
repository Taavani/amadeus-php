<?php
/**
 * This file contains a class to represent a queue in the Amadeus API.
 *
 * Class Queue
 * @namespace Amadeus\Resources\Client
 */

namespace Amadeus\Resources\Client;

/**
 * Class Queue
 * @package Amadeus\Resources\Client
 */
class Queue
{
    private string $number;
    private string $category;

    /**
     * Queue constructor.
     */
    public function __construct()
    {
        $this->number = '';
        $this->category = '';
    }

    /**
     * Set the number of the queue.
     *
     * @param string $number
     * @return Queue
     */
    public function setNumber(string $number): Queue
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function setCategory(string $category): Queue
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'number' => $this->number,
            'category' => $this->category
        ];
    }
}
