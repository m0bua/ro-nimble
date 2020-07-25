<?php

namespace App\Processors;

use App\Consumers\PromoGoodsConsumer;
use App\ValueObjects\Message;
use App\ValueObjects\RoutingKey;
use Bschmitt\Amqp\Exception\Configuration;
use Exception;
use Illuminate\Console\Command;

class ChangePromotionConstructorProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        app('redis')->set(
            $this->message->getField('fields_data.id'),
            json_encode([
                'promotion_id' => $this->message->getField('fields_data.promotion_id'),
                'gift_id' => $this->message->getField('fields_data.gift_id'),
            ])
        );
    }
}
