<?php

namespace App\Processors\GoodsService;

use App\Console\Commands\IndexRefill;
use App\Models\Eloquent\Goods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;
use Illuminate\Support\Facades\Artisan;

class CreateGoodsEntityProcessor extends AbstractProcessor
{
    use WithCreate;

    protected Goods $model;

    /**
     * CreateGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }

    public function afterProcess(): void
    {
        Artisan::call(IndexRefill::class, ['--goods-ids' => $this->data['id']]);
    }
}
