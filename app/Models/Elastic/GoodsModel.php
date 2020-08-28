<?php

namespace App\Models\Elastic;

/**
 * Class GoodsModel
 * @package App\Models\Elastic\Promotions
 */
class GoodsModel extends Elastic
{
    protected $id;
    protected $promotion_constructors = [];
    protected $category_id;
    protected $categories_path;
    protected $producer_id;
    protected $producer_name;
    protected $price;
    protected $sell_status;
    protected $seller_order;
    protected $seller_id;
    protected $group_id;
    protected $is_group_primary;
    protected $status_inherited;
    protected $order;
    protected $series_id;
    protected $state;
    protected $tags;
    protected $pl_bonus_charge_pcs;
    protected $search_rank;
    protected $options;
    protected $option_names;
    protected $option_values;
    protected $option_values_names;
    protected $option_checked;
    protected $option_checked_names;
    protected $option_sliders;

    /**
     * @return string
     */
    public function indexName(): string
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
        return $this->searchTermByField('id', $goodsId);
    }

    /**
     * @param $fieldName
     * @param $value
     * @return array|mixed
     */
    public function searchTermByField($fieldName, $value)
    {
        $searchResult = $this->search(
            [
                'body' => [
                    'query' => [
                        'term' => [$fieldName => $value],
                    ],
                ],
            ]
        );

        $source = $this->getSource($searchResult);
        return isset($source[0]) ? $source[0] : $source;
    }
}
