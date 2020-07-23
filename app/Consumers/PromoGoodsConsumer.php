<?php


namespace App\Consumers;


use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Exception\Configuration;
use Closure;
use Illuminate\Support\Facades\Log;

class PromoGoodsConsumer extends ConsumerClosure
{
    /**
     * @var string
     */
    protected $queueName = 'promotion.push.goods';

    /**
     * @param Closure|null $callback
     * @param RoutingKey|null $routingKey
     * @return mixed|void
     * @throws Configuration
     */
    public function consume(Closure $callback, RoutingKey $routingKey)
    {
        (new Amqp())->consume($this->queueName, function ($message, $resolver) use ($callback, $routingKey) {
            try {
                $routingKeyNeedle = $routingKey->get();
                $routingKeyMessage = $message->delivery_info['routing_key'];

                if ($routingKeyNeedle === $routingKeyMessage) {
                    $callback($message, $resolver);
                }
            } catch (\Throwable $t) {
//                Log::error("{$t->getMessage()}; File: {$t->getFile()}; Line: {$t->getLine()}");
                abort(500, "{$t->getMessage()}; File: {$t->getFile()}; Line: {$t->getLine()}");
            }
        });
    }
}
