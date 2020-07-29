<?php

namespace App\Processors;

use App\ValueObjects\Processor;
use Exception;

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

        return Processor::CODE_SUCCESS;
    }
}
