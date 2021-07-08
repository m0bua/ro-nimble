<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;
use Illuminate\Support\Arr;

class ChangeGoodsOptionPluralProcessor extends AbstractProcessor
{
    use WithUpdate;

    public static ?array $compoundKey = [
        'goods_id',
        'option_id',
        'value_id',
    ];

    protected GoodsOptionPlural $model;

    protected Goods $goods;

    /**
     * ChangeGoodsOptionPluralProcessor constructor.
     * @param GoodsOptionPlural $model
     * @param Goods $goods
     */
    public function __construct(GoodsOptionPlural $model, Goods $goods)
    {
        $this->model = $model;
        $this->goods = $goods;
    }

    /**
     * Update entity in DB
     *
     * @return bool
     */
    protected function updateModel(): bool
    {
        $data = $this->prepareData();
        $data['needs_index'] = 1;

        $this->model
            ->when(
                static::$compoundKey,
                fn($q, $compoundKey) => $q->where(Arr::only($this->data, $compoundKey)),
                fn($q) => $q->where('id', $this->data['id'])
            )
            ->update($data);
        $this->saveTranslations();

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goods->whereId($this->data['goods_id'])->update(['needs_index' => 1]);
    }
}
