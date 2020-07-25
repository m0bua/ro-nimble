<?php

namespace App\Processors;

use Exception;

class DeletePromotionConstructorProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        app('redis')->unlink($this->message->getField('fields_data.id'));
    }
}
