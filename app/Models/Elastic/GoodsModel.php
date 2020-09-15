<?php

namespace App\Models\Elastic;

use App\Models\GraphQL\ProducerOneModel;
use Exception;

/**
 * Class GoodsModel
 * @package App\Models\Elastic\Promotions
 *
 * @property integer $is_group_primary
 */
class GoodsModel extends Elastic
{
    protected ?int $id                       = null;
    protected ?array $promotion_constructors = [];
    protected ?int $category_id              = null;
    protected ?array $categories_path        = null;
    protected ?int $producer_id              = null;
    protected ?string $producer_title        = null;
    protected ?string $producer_name         = null;
    protected ?float $price                  = null;
    protected ?string $sell_status           = null;
    protected ?int $seller_order             = null;
    protected ?int $seller_id                = null;
    protected ?int $group_id                 = null;
    protected ?int $is_group_primary         = null;
    protected ?string $status_inherited      = null;
    protected ?int $order                    = null;
    protected ?int $series_id                = null;
    protected ?string $state                 = null;
    protected ?array $tags                   = [];
    protected ?int $pl_bonus_charge_pcs      = null;
    protected ?float $search_rank            = null;
    protected ?array $options                = [];
    protected ?array $option_names           = [];
    protected ?array $option_values          = [];
    protected ?array $option_values_names    = [];
    protected ?array $option_checked         = [];
    protected ?array $option_checked_names   = [];
    protected ?array $option_sliders         = [];

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
     * @throws Exception
     */
    public function index(array $params = [])
    {
        return parent::index(
            array_merge(['id' => $this->getAttribute('id')], $params)
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
     * @param string $fieldName
     * @param mixed $value
     * @return array
     */
    public function searchTermByField(string $fieldName, $value): array
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

        return $this->getSource($searchResult);
    }

    /**
     * @param int $producerId
     * @throws Exception
     */
    public function setProducerIdWithExtra(int $producerId)
    {
        $producerTitle = null;
        $producerName = null;

        if ($producerId !== 0) {
            $producer = (new ProducerOneModel())
                ->setSelectionSet(['title', 'name'])
                ->setArgumentsWhere(
                    'id_eq',
                    $producerId
                )
                ->get();

            $producerTitle = $producer['title'];
            $producerName = $producer['name'];
        }


        $this->setField('producer_title', $producerTitle);
        $this->setField('producer_name', $producerName);
        $this->setField('producer_id', $producerId);
    }
}
