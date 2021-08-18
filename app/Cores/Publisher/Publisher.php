<?php

namespace App\Cores\Publisher;

use Bschmitt\Amqp\Exception\Configuration;
use Bschmitt\Amqp\Message;
use Bschmitt\Amqp\Publisher as BasePublisher;
use Illuminate\Contracts\Container\BindingResolutionException;

class Publisher
{
    /**
     * @var BasePublisher publisher
     */
    protected BasePublisher $publisher;

    /**
     * Publisher constructor.
     * @throws BindingResolutionException|Configuration
     */
    public function __construct()
    {
        $this->setup();
    }

    /**
     * Publish message
     *
     * @param string $routing
     * @param Message $message
     * @param string $exchange
     * @return bool|null
     * @throws Configuration
     */
    public function publish(string $routing, Message $message, string $exchange = ''): ?bool
    {
        if (!empty($exchange)) {
            $this->publisher->mergeProperties([
                'exchange' => $exchange,
            ]);
        }

        return $this->publisher->publish($routing, $message);
    }

    /**
     * @return void
     * @throws BindingResolutionException|Configuration
     */
    private function setup(): void
    {
        config(['amqp.use' => 'publisher']);
        $this->publisher = app()->make(BasePublisher::class);
        $this->publisher->setup();
    }
}
