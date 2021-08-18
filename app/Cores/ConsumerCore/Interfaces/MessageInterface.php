<?php


namespace App\Cores\ConsumerCore\Interfaces;


interface MessageInterface
{
    /**
     * Get decoded message body
     *
     * @return mixed
     */
    public function getBody();

    /**
     * Get message routing key
     *
     * @return string
     */
    public function getRoutingKey(): string;

    /**
     * Get nested field separated by dots
     *
     * @param string $fieldRoute
     * @return mixed
     */
    public function getField(string $fieldRoute);
}
