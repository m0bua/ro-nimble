<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\Message;
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
        (new PromoGoodsConsumer())->consume(function ($amqpMessage, $resolver) {
            $message = new Message($amqpMessage);
            app('redis')->set(
                $message->getField('fields_data.id'),
                json_encode([
                    'promotion_id' => $message->getField('fields_data.promotion_id'),
                    'gift_id' => $message->getField('fields_data.gift_id'),
                ])
            );
//            $resolver->acknowledge($amqpMessage);
        }, new RoutingKey($this->routingKey));
    }
}
