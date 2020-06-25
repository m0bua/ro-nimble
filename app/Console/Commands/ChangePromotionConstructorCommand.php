<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\Message;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
            try {
                $message = new Message($amqpMessage);
                app('redis')->set(
                    $message->getField('fields_data.id'),
                    $message->getField('fields_data.promotion_id')
                );
            } catch (\Throwable $t) {
                Log::error($t->getMessage());
            }
//            $resolver->acknowledge($amqpMessage);
        }, new RoutingKey($this->routingKey));
    }
}
