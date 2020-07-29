<?php

namespace App\Processors;

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
