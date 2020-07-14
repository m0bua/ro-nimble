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

    protected $id; // id
    protected $promotion_id; // promotion_id
    protected $constructor_id; // constructor_id
    protected $gift_id; // gift_id
    protected $category_id; // category_id
    protected $category_ids; // category_ids:mpath
    protected $producer_id; // producer
    protected $producer_name; // producer
    protected $price; // price
    protected $sell_status; // sell_status
    protected $seller_order; // 'seller_id' == 5 ? 1 : 0
    protected $seller_id; // seller_id
    protected $group_id; // group_id
    protected $is_group_primary; // is_group_primary
    protected $status_inherited; // status_inherited
    protected $goods_order; // goods_order:order
    protected $series_id; // series_id
    protected $state; // state


    // ждем реалізацію від гудсов
    protected $rank; //goods_ranks->search_rank
    protected $income_order; //goods_ranks->search_rank
    protected $bonus_charge; //bonus_charge:pl_bonus_charge_pcs



    protected $options;
    protected $option_names;
    protected $option_values;
    protected $option_values_names;
    protected $options_checked;
    protected $option_sliders;

    // в маркетинг будемо ходити
    protected $tags;

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
