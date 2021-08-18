<?php

namespace App\Messages\IndicesRefill;

use App\Messages\RabbitMqMessage;
use Bschmitt\Amqp\Message;

class GoodsOrderMessage extends RabbitMqMessage
{
    /**
     * @var array Array of goods ids
     */
    private array $goods;

    /**
     * @param int $start
     * @param int $end
     */
    public function __construct(array $goods)
    {
        $this->goods = $goods;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'data' => $this->goods,
            'serviceData' => $this->serviceData,
        ];
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0): string
    {
        /** @noinspection JsonEncodingApiUsageInspection */
        return json_encode($this->toArray(), $options);
    }

    /**
     * @inheritDoc
     */
    public function build(): Message
    {
        return new Message($this->toJson());
    }

    /**
     * @inheritDoc
     */
    public function getRoutingKey(): string
    {
        return 'indices.goods.range';
    }
}
