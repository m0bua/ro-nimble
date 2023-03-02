<?php
/**
 * Базовый класс компонентов SortComponent
 * Class SortComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Elastic;

abstract class BaseSortComponent extends BaseComponent
{
    /**
     * @return array
     */
    abstract protected function getScript(): array;

    /**
     * @return array[]
     */
    protected function getIsGroupPrimary(): array
    {
        return [
            'is_group_primary' => [
                'order' => 'desc'
            ]
        ];
    }

    /**
     * @return array[]
     */
    protected function getPromotionOrder(): array
    {
        return isset($this->filters->promotion->getValues()[0])
            ? [
                'promotion.order' => [
                    'order' => 'asc',
                    'nested' => [
                        'path' => Elastic::FIELD_PROMOTION,
                        'filter' => $this->elasticWrapper->term(
                            Elastic::FIELD_PROMOTION_ID,
                            $this->filters->promotion->getValues()[0]
                        )
                    ]
                ]
            ]
            : [];
    }

    /**
     * @return array[]
     */
    protected function getOrder(): array
    {
        return [
            'order' => [
                'order' => 'asc'
            ]
        ];
    }

    /**
     * @return array[]
     */
    protected function getRank(): array
    {
        return [
            'rank' => [
                'order' => 'desc'
            ]
        ];
    }

    /**
     * @return array[]
     */
    protected function getId(): array
    {
        return [
            'id' => [
                'order' => 'desc'
            ]
        ];
    }

    /**
     * @return array
     */
    abstract public function getValueForCollapse(): array;

    /**
     * @param array $primary
     * @param array $secondary
     * @param bool $isPromotion
     * @return array
     */
    abstract public function getMainProductBySort(array $primary, array $secondary, bool $isPromotion): array;

    /**
     * @param array $data
     * @param bool $isPromotion
     * @return array
     */
    abstract public function sortResultArray(array $data, bool $isPromotion): array;
}
