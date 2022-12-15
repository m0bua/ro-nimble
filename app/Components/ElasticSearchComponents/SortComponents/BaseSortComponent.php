<?php
/**
 * Базовый класс компонентов SortComponent
 * Class SortComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Elastic;
use Illuminate\Support\Collection;

abstract class BaseSortComponent extends BaseComponent
{
    /**
     * @return array
     */
    abstract protected function getScript(): array;

    /**
     * Calculates order by `script`
     *
     * @param \stdClass $product
     * @return int
     */
    abstract protected static function getScriptOrder(\stdClass $product): int;

    /**
     * Returns order in group
     *
     * @param Collection $data
     * @return Collection
     */
    abstract public static function getOrderInGroup(Collection $data): Collection;

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
     * Calculates order in group
     *
     * @param Collection $data
     * @param array $sort
     * @return Collection
     */
    protected final static function calcOrderInGroup(Collection $data, array $sort): Collection
    {
        $orderCollection = collect([]);
        foreach ($data as $product) {
            $orderCollection = $orderCollection->merge([[
                'script'           => static::getScriptOrder($product),
                'price'            => \round($product->price, 2),
                'is_group_primary' => (int) $product->is_group_primary,
                'order'            => (int) $product->order,
                'rank'             => (int) $product->rank,
                'id'               => (int) $product->id,
            ]]);
        }

        return $orderCollection->sortBy($sort);
    }
}
