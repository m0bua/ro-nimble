<?php

namespace App\Processors\MarketEnterprise\ProductsType;

use App\Console\Commands\IndexRefill;
use App\Console\Kernel;
use App\Models\Eloquent\GoodsCarInfo;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteEventProcessor extends AbstractProcessor
{
    use WithDelete;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        'goods_id',
        'car_trim_id',
    ];

    /**
     * Model
     *
     * @var GoodsCarInfo
     */
    protected GoodsCarInfo $model;

    /**
     * Artisan instance
     *
     * @var Kernel
     */
    protected Kernel $artisan;

    /**
     * @param GoodsCarInfo $model
     * @param Kernel $artisan
     */
    public function __construct(GoodsCarInfo $model, Kernel $artisan)
    {
        $this->model = $model;
        $this->artisan = $artisan;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->artisan->call(IndexRefill::class, ['--goods-ids' => $this->data['goods_id']]);
    }
}
