<?php

namespace App\Processors\MarketingService\PromotionConstructorGroup;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\IndexGoods;
use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\DeleteProcessor;

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

    private IndexGoods $indexGoods;

    /**
     * @param PromotionGroupConstructor $model
     * @param Goods $goods
     * @param IndexGoods $indexGoods
     */
    public function __construct(
        PromotionGroupConstructor $model,
        Goods                     $goods,
        IndexGoods                $indexGoods
    )
    {
        $this->model = $model;
        $this->goods = $goods;
        $this->indexGoods = $indexGoods;
    }

    protected function afterProcess(): void
    {
        $goods = $this->goods->query()
            ->select('id')
            ->where('group_id', '=', $this->data['group_id'])
            ->get();

        $this->indexGoods->query()->insertOrIgnore($goods->toArray());
    }
}
