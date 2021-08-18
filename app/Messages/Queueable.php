<?php

namespace App\Messages;

use Bschmitt\Amqp\Message;
use Illuminate\Contracts\Support;

interface Queueable extends Support\Arrayable, Support\Jsonable
{
    /**
     * Build message for RabbitMQ
     *
     * @return Message
     */
    public function build(): Message;

    /**
     * Get message's routing key
     *
     * @return string
     */
    public function getRoutingKey(): string;
}
