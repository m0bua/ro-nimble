<?php


namespace App\Consumers;


use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Exception\Configuration;
use Closure;

class PromoGoodsConsumer extends ConsumerClosure
{
    /**
     * @var string
     */
    protected $queueName = 'promo_goods_local';

    /**
     * @param Closure|null $callback
     * @param RoutingKey|null $routingKey
     * @return mixed|void
     * @throws Configuration
     */
    public function consume(Closure $callback, RoutingKey $routingKey)
    {
        (new Amqp())->consume($this->queueName, function ($message, $resolver) use ($callback, $routingKey) {
            $routingKeyNeedle = $routingKey->get();
            $routingKeyMessage = $message->delivery_info['routing_key'];

            if ($routingKeyNeedle === $routingKeyMessage) {
                $callback($message, $resolver);
            }
        });
    }
}
