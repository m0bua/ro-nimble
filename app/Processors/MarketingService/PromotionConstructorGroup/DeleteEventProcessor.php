<?php

namespace App\Processors\MarketingService\PromotionConstructorGroup;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\DeleteProcessor;
use App\Services\Buffers\RedisGoodsBufferService;
use Illuminate\Support\Facades\Redis;

class DeleteEventProcessor extends DeleteProcessor
{
    private const CONSTRUCTOR_ID_KEY = 'constructor_id';
    private const GROUP_ID_KEY = 'group_id';

    protected string $dataRoot = 'fields_data';

    protected array $compoundKey = [
        self::CONSTRUCTOR_ID_KEY,
        self::GROUP_ID_KEY,
    ];

    protected array $aliases = [
        'promotion_constructor_id' => self::CONSTRUCTOR_ID_KEY,
    ];

    private Goods $goods;
    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param PromotionGroupConstructor $model
     * @param Goods $goods
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(
        PromotionGroupConstructor $model,
        Goods                     $goods,
        RedisGoodsBufferService   $goodsBuffer
    )
    {
        $this->model = $model;
        $this->goods = $goods;
        $this->goodsBuffer = $goodsBuffer;
    }

    protected function afterProcess(): void
    {
        $goods = $this->goods->query()
            ->select('id')
            ->where('group_id', '=', $this->data['group_id'])
            ->get();

        $this->goodsBuffer->radd($goods->toArray());
    }
}
