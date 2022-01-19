<?php

namespace App\Processors\GoodsService\GoodsOptionPlural;

use App\Models\Eloquent\GoodsOptionPlural;
use App\Models\Eloquent\IndexGoods;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'goods_id',
        'option_id',
        'value_id',
    ];

    private IndexGoods $indexGoods;

    /**
     * @param GoodsOptionPlural $model
     * @param IndexGoods $indexGoods
     */
    public function __construct(GoodsOptionPlural $model, IndexGoods $indexGoods)
    {
        $this->model = $model;
        $this->indexGoods = $indexGoods;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        $data = parent::prepareData();
        $data['needs_index'] = 1;

        return $data;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->indexGoods->query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
