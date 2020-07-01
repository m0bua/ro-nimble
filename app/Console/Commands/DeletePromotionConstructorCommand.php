<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\Message;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class DeletePromotionConstructorCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consumer:delete-promotion-constructor';

    /**
     * @var string
     */
    protected $description = 'AMQP consumer for deleting promotion constructor';

    /**
     * @var string
     */
    protected $routingKey = 'delete.Promotion_Constructor.record';

    /**
     * Execute the console command
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($amqpMessage, $resolver) {
            $message = new Message($amqpMessage);
            app('redis')->unlink($message->getField('fields_data.id'));
//            $resolver->acknowledge($amqpMessage);
        }, new RoutingKey($this->routingKey));
    }
}
