<?php

namespace App\Models\Elastic\Promotions;

use App\Models\Elastic\Elastic;
use App\ValueObjects\Property;

class GoodsModel extends Elastic
{
    use PromotionsTrait;

    public $id;
    public $promotion_id;
    public $constructor_id;
    public $gift_id;
    public $category_id;
    public $category_ids;
    public $options;
    public $option_names;
    public $option_values;
    public $option_values_names;
    public $options_checked;
    public $option_sliders;
    public $producer_id;
    public $producer_name;
    public $price;
    public $rank;
    public $sell_status;
    public $status_inherited;
    public $seller_order;
    public $seller_id;
    public $income_order;
    public $group_id;
    public $is_group_primary;
    public $goods_order;
    public $tags;
    public $bonus_charge;
    public $series_id;
    public $state;

    /**
     * @inheritDoc
     */
    public function typeName(): string
    {
        return 'goods';
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return get_object_vars($this);
    }

    /**
     * Ищет товар по ID
     *
     * @param int $goodsId
     * @return array|callable
     */
    public function searchById(int $goodsId)
    {
        return $this->search(
            [
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $goodsId,
                            'fields' => ['id'],
                        ],
                    ],
                ],
            ]
        );
    }
}
