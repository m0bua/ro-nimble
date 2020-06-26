<?php


namespace App\ValueObjects;


class RoutingKey
{
    /**
     * @var string
     */
    private $routingKey;

    /**
     * RoutingKey constructor.
     * @param string $routingKey
     */
    public function __construct(string $routingKey)
    {
        $this->routingKey = $routingKey;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->routingKey;
    }
}
