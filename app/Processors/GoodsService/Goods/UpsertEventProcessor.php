<?php

namespace App\Processors\GoodsService\Goods;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\IndexGoods;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    private IndexGoods $indexGoods;

    /**
     * @param Goods $model
     * @param IndexGoods $indexGoods
     */
    public function __construct(Goods $model, IndexGoods $indexGoods)
    {
        $this->model = $model;
        $this->indexGoods = $indexGoods;
    }

    /**
     * @return void
     */
    protected function afterProcess(): void
    {
        $this->indexGoods->query()->insertOrIgnore(['id' => $this->data['id']]);
    }
}
