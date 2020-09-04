<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use ReflectionException;

class ChangeGoodsEntityProcessor extends AbstractCore
{
    /**
     * @return int
     * @throws ReflectionException
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();
        $goodsId = $this->message->getField('data.id');
        $goodsData = (array)$this->message->getField('data');

        $currentData = $elasticGoodsModel->searchById($goodsId);

        if (!empty($currentData)) {
            $elasticGoodsModel->load($currentData);

            $commonFormatter = new CommonFormatter($goodsData);
            $commonFormatter->formatGoodsForIndex();
            $formattedData = $commonFormatter->getFormattedData();
            $elasticGoodsModel->load($formattedData)->index();

            return Processor::CODE_SUCCESS;
        }

        return Processor::CODE_SKIP;
    }
}
