<?php

namespace App\Processors\MarketingService\PromotionConstructorGoods;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\DeleteProcessor;
use App\Services\Buffers\RedisGoodsBufferService;
use Illuminate\Support\Facades\Redis;

class DeleteEventProcessor extends DeleteProcessor
{
    private const CONSTRUCTOR_ID_KEY = 'constructor_id';
    private const GOODS_ID_KEY = 'goods_id';

    protected string $dataRoot = 'fields_data';

    protected array $compoundKey = [
        self::CONSTRUCTOR_ID_KEY,
        self::GOODS_ID_KEY,
    ];

    protected array $aliases = [
        'promotion_constructor_id' => self::CONSTRUCTOR_ID_KEY,
    ];

    private GoodsBuffer $goodsBuffer;

    /**
     * @param PromotionGoodsConstructor $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(PromotionGoodsConstructor $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
