<?php

namespace App\Console\Commands;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\Message;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
            try {
                $message = new Message($amqpMessage);
                app('redis')->unlink($message->getField('id'));
            } catch (\Throwable $t) {
                Log::error($t->getMessage());
            }
//            $resolver->acknowledge($amqpMessage);
        }, new RoutingKey($this->routingKey));
    }
}
