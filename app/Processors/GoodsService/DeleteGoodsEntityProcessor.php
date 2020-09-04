<?php

namespace App\Processors\GoodsService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;

class DeleteGoodsEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        (new GoodsModel())->delete(['id' => $this->message->getField('id')]);

        return Processor::CODE_SUCCESS;
    }
}
