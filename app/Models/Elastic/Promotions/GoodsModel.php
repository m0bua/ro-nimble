<?php

namespace App\Models\Elastic\Promotions;

use App\Models\Elastic\Elastic;
use App\ValueObjects\Property;

/**
 * Class GoodsModel
 * @package App\Models\Elastic\Promotions
 */
class GoodsModel extends PromotionsElastic
{
    use PromotionsTrait;

    protected $id;
    protected $promotion_id;
    protected $constructor_id;
    protected $gift_id;
    protected $category_id;
    protected $category_ids;
    protected $producer_id;
    protected $producer_name;
    protected $price;
    protected $sell_status;
    protected $seller_order;
    protected $seller_id;
    protected $group_id;
    protected $is_group_primary;
    protected $status_inherited;
    protected $goods_order;
    protected $series_id;
    protected $state;
    protected $tags;
    protected $options;
    protected $option_names;
    protected $option_values;
    protected $option_values_names;
    protected $options_checked;
    protected $option_sliders;


    // ждем реалізацію від гудсов
    protected $rank; //goods_ranks->search_rank
    protected $income_order; //goods_ranks->search_rank
    protected $bonus_charge; //bonus_charge:pl_bonus_charge_pcs





    /**
     * @inheritDoc
     */
    public function typeName(): string
    {
        return 'goods';
    }

    /**
     * @inheritDoc
     */
    public function requiredFields(): array
    {
        return ['id'];
    }

    /**
     * @param array $params
     * @return array|callable
     * @throws \ReflectionException
     */
    public function index(array $params = [])
    {
        return parent::index(
            array_merge($this->getFields(['id']), $params)
        );
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
