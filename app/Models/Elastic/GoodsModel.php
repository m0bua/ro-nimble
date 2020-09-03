<?php

namespace App\Models\Elastic;

/**
 * Class GoodsModel
 * @package App\Models\Elastic\Promotions
 *
 * @property integer $is_group_primary
 */
class GoodsModel extends Elastic
{
    protected ?int $id;
    protected ?array $promotion_constructors = null;
    protected ?int $category_id              = null;
    protected ?array $categories_path        = null;
    protected ?int $producer_id              = null;
    protected ?string $producer_title        = null;
    protected ?string $producer_name         = null;
    protected ?int $price                    = null;
    protected ?string $sell_status           = null;
    protected ?int $seller_order             = null;
    protected ?int $seller_id                = null;
    protected ?int $group_id                 = null;
    protected ?int $is_group_primary         = null;
    protected ?string $status_inherited      = null;
    protected ?int $order                    = null;
    protected ?int $series_id                = null;
    protected ?string $state                 = null;
    protected ?array $tags                   = null;
    protected ?int $pl_bonus_charge_pcs      = null;
    protected ?float $search_rank            = null;
    protected ?array $options                = null;
    protected ?array $option_names           = null;
    protected ?array $option_values          = null;
    protected ?array $option_values_names    = null;
    protected ?array $option_checked         = null;
    protected ?array $option_checked_names   = null;
    protected ?array $option_sliders         = null;

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
     * @inheritDoc
     */
    public function typeIndication(): array
    {
        return [
            'is_group_primary' => [
                'own_type' => 'integer',
                'possible_types' => ['integer', 'boolean']
            ],
        ];
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
