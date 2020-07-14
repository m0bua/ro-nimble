<?php

namespace App\Models\Elastic\Promotions;

use App\Models\Elastic\Elastic;
use App\ValueObjects\Property;

/**
 * Class GoodsModel
 * @package App\Models\Elastic\Promotions
 */
class GoodsModel extends Elastic
{
    use PromotionsTrait;

    protected $id;
    protected $promotion_id;
    protected $constructor_id;
    protected $gift_id;
    protected $category_id;
    protected $category_ids;
    protected $options;
    protected $option_names;
    protected $option_values;
    protected $option_values_names;
    protected $options_checked;
    protected $option_sliders;
    protected $producer_id;
    protected $producer_name;
    protected $price;
    protected $rank;
    protected $sell_status;
    protected $status_inherited;
    protected $seller_order;
    protected $seller_id;
    protected $income_order;
    protected $group_id;
    protected $is_group_primary;
    protected $goods_order;
    protected $tags;
    protected $bonus_charge;
    protected $series_id;
    protected $state;

    /**
     * @inheritDoc
     */
    public function typeName(): string
    {
        return 'goods';
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
