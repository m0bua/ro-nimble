<?php

namespace App\Processors\GoodsService;

use App\Console\Commands\IndexRefill;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\IndexGoods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;
use Illuminate\Support\Facades\Artisan;

class ChangeGoodsEntityProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected Goods $model;

    /**
     * ChangeGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }

    /**
     * @return void
     */
    protected function afterProcess(): void
    {
        IndexGoods::query()->insertOrIgnore(['id' => $this->data['id']]);
    }
}
