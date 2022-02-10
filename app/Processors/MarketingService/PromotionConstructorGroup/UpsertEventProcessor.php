<?php

namespace App\Processors\MarketingService\PromotionConstructorGroup;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    private const CONSTRUCTOR_ID_KEY = 'constructor_id';
    private const GROUP_ID_KEY = 'group_id';

    protected array $compoundKey = [
        self::CONSTRUCTOR_ID_KEY,
        self::GROUP_ID_KEY,
    ];

    protected string $dataRoot = 'fields_data';

    protected array $aliases = [
        'promotion_constructor_id' => self::CONSTRUCTOR_ID_KEY,
    ];

    private Goods $goods;
    private GoodsBuffer $goodsBuffer;

    /**
     * @param PromotionGroupConstructor $model
     * @param Goods $goods
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(
        PromotionGroupConstructor $model,
        Goods                     $goods,
        GoodsBuffer $goodsBuffer
    )
    {
        $this->model = $model;
        $this->goods = $goods;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * Update or create entity
     *
     * @param array<string>|string $uniqueBy
     * @param array<string>|null $update
     * @return bool
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        $data['needs_index'] = 1;
        $data['needs_migrate'] = 1;
        $data['is_deleted'] = 0;

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
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
