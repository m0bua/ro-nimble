<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\GoodsOption;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\IndexRefill;

class SyncGoodsOptionProcessor extends AbstractProcessor
{
    use WithUpsert;

    public static array $uniqueBy  = ['goods_id', 'option_id', 'type'];
    protected GoodsOption $model;

    /**
     * ChangeGoodsOptionProcessor constructor.
     * @param GoodsOption $model
     */
    public function __construct(GoodsOption $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        Artisan::call(IndexRefill::class, ['--goods-ids' => $this->data['goods_id']]);
    }
}
