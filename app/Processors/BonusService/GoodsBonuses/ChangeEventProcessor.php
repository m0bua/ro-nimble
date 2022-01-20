<?php

namespace App\Processors\BonusService\GoodsBonuses;

use App\Models\Eloquent\Bonus;
use App\Models\Eloquent\IndexGoods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeEventProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected Bonus $model;

    public static ?string $dataRoot = 'fields_data';

    protected static array $aliases = [
        'pl_comment_bonus_charge' => 'comment_bonus_charge',
        'pl_comment_photo_bonus_charge' => 'comment_photo_bonus_charge',
        'pl_comment_video_bonus_charge' => 'comment_video_bonus_charge',
        'pl_bonus_not_allowed_pcs' => 'bonus_not_allowed_pcs',
        'pl_comment_video_child_bonus_charge' => 'comment_video_child_bonus_charge',
        'pl_bonus_charge_pcs' => 'bonus_charge_pcs',
        'pl_use_instant_bonus' => 'use_instant_bonus',
        'pl_premium_bonus_charge_pcs' => 'premium_bonus_charge_pcs',
    ];

    /**
     * ChangeEventProcessor constructor.
     * @param Bonus $model
     */
    public function __construct(Bonus $model)
    {
        $this->model = $model;
    }

    /**
     * Update entity in DB
     *
     * @return bool
     */
    protected function updateModel(): bool
    {
        $data = $this->prepareData();
        $this->model->whereGoodsId($data['goods_id'])->update($data);
        $this->saveTranslations();

        return true;
    }

    protected function afterProcess(): void
    {
        IndexGoods::query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
