<?php


namespace App\Console\Commands;


use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\Message;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Console\Command;

class ConsumePromotionGoodsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'consume-promotion-goods';

    /**
     * @var string
     */
    protected $description = 'AMQP Consumer for creating or changing promotion goods';

    /**
     * @throws Configuration
     */
    public function handle()
    {
        (new PromoGoodsConsumer())->consume(function ($amqpMessage, $resolver) {
            $message = new Message($amqpMessage);
            $routingKey = $message->routingKey();

            $processor = $routingKey->prepareProcessor();
            $processor->setMessage($message);
            $processor->run();

//            $resolver->acknowledge($amqpMessage);
        });

    }
}
