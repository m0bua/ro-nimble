<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class DeletePromotionConstructorGoodsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consumer:delete-promotion-constructor-goods';

    /**
     * @var string
     */
    protected $description = 'AMQP consumer for deleting promotion goods';

    /**
     * @var string
     */
    protected $routingKey = 'delete.Promotion_Constructor_Goods.record';

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
