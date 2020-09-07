<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use ReflectionException;

class CreateGoodsEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();
        $goodsData = (array)$this->message->getField('data');

        $commonFormatter = new CommonFormatter($goodsData);
        $commonFormatter->formatGoodsForIndex();
        $formattedData = $commonFormatter->getFormattedData();

        $elasticGoodsModel
            ->load($formattedData, ['producer_id' => 'setProducerIdWithExtra'])
            ->index();

        return Processor::CODE_SUCCESS;
    }
}
