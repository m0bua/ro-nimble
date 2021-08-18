<?php

namespace App\Messages\IndicesRefill;

use App\Messages\RabbitMqMessage;
use Bschmitt\Amqp\Message;

class RangeMessage extends RabbitMqMessage
{
    /**
     * Start of range
     *
     * @var int
     */
    private int $start;

    /**
     * End of range
     *
     * @var int
     */
    private int $end;

    /**
     * @param int $start
     * @param int $end
     */
    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Make instance from array
     *
     * @param array $range
     * @return RangeMessage
     */
    public static function fromArray(array $range): RangeMessage
    {
        return new static($range[0], $range[1]);
    }

    /**
     * Make instance from associative array
     *
     * @param array $range
     * @return RangeMessage
     */
    public static function fromAssocArray(array $range): RangeMessage
    {
        return new static($range['start'], $range['end']);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'data' => [
                'min' => $this->start,
                'max' => $this->end,
            ],
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
