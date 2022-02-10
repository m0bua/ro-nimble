<?php

namespace App\Processors\BonusService\GoodsBonuses;

use App\Models\Eloquent\Bonus;
use App\Processors\UpsertProcessor;
use App\Services\Buffers\RedisGoodsBufferService;

class UpsertEventProcessor extends UpsertProcessor
{
    protected string $dataRoot = 'fields_data';

    protected array $aliases = [
        'pl_comment_bonus_charge' => 'comment_bonus_charge',
        'pl_comment_photo_bonus_charge' => 'comment_photo_bonus_charge',
        'pl_comment_video_bonus_charge' => 'comment_video_bonus_charge',
        'pl_bonus_not_allowed_pcs' => 'bonus_not_allowed_pcs',
        'pl_comment_video_child_bonus_charge' => 'comment_video_child_bonus_charge',
        'pl_bonus_charge_pcs' => 'bonus_charge_pcs',
        'pl_use_instant_bonus' => 'use_instant_bonus',
        'pl_premium_bonus_charge_pcs' => 'premium_bonus_charge_pcs',
    ];

    protected array $compoundKey = [
        'goods_id',
    ];

    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param Bonus $model
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(Bonus $model, RedisGoodsBufferService $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
