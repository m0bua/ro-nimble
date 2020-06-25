<?php

namespace App\Consumers;

use App\ValueObjects\RoutingKey;
use Closure;

abstract class ConsumerClosure
{
    /**
     * @var string
     */
    protected $queueName;

    /**
     * @param Closure $callback
     * @param RoutingKey $routingKey
     * @return mixed
     */
    abstract public function consume(Closure $callback, RoutingKey $routingKey);
}
