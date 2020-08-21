<?php

namespace App\Processors\MarketingService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Exception;

class DeletePromotionConstructorProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        app('redis')->unlink($this->message->getField('fields_data.id'));

        return Processor::CODE_SUCCESS;
    }
}
