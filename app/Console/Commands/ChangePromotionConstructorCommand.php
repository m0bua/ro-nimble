<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class ChangePromotionConstructorCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "consumer:change-promotion-constructor";

    /**
     * @var string
     */
    protected $description = "AMQP Consumer for creating or changing promotion constructor";

    /**
     * @var string
     */
    protected $routingKey = 'change.Promotion_Constructor.record';

    /**
     * Execute the console command
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($message, $resolver) {
            // TODO
            var_dump($message->body);
        }, new RoutingKey($this->routingKey));
    }
}
