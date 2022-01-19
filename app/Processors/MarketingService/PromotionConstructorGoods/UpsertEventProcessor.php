<?php

namespace App\Processors\MarketingService\PromotionConstructorGoods;

use App\Models\Eloquent\IndexGoods;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
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

    private IndexGoods $indexGoods;

    /**
     * @param PromotionGoodsConstructor $model
     * @param IndexGoods $indexGoods
     */
    public function __construct(PromotionGoodsConstructor $model, IndexGoods $indexGoods)
    {
        $this->model = $model;
        $this->indexGoods = $indexGoods;
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
        $this->indexGoods->query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
