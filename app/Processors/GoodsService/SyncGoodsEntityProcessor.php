<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\IndexRefill;

class SyncGoodsEntityProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected Goods $model;
    public static array $uniqueBy = ['id'];

    /**
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        Artisan::call(IndexRefill::class, ['--goods-ids' => $this->data['id']]);
    }
}
