<?php
/**
 * Класс для создания кастомного фильтра "Товары с акциями"
 * Class GoodsWithPromotionsService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Config;
use App\Enums\Elastic;
use App\Enums\Filters;

class GoodsWithPromotionsService extends BaseComponent
{
    /** @var array $currentCustomFiltersConditions */
    private array $currentCustomFiltersConditions;

    /**
     * @var string
     */
    private string $typeCount = 'installment';

    /**
     * @var array
     */
    private $aggData = [
        Filters::PROMOTION_GOODS_INSTALLMENT => null,
        Filters::PROMOTION_GOODS_PROMOTION => null
    ];

    /**
     * @param array $filterComponent
     * @return void
     */
    private function setCurrentFilterQuery(array $filterComponent): void
    {
        $this->currentCustomFiltersConditions = $filterComponent;
    }

    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->elasticWrapper->aggs(
            [
                $this->typeCount . '_types_count' => [
                    'value_count' => [
                        'field' => 'id'
                    ]
                ]
            ]
        );
    }

    /**
     * @return array
     */
    public function getCustomFiltersConditions(): array
    {
        return $this->currentCustomFiltersConditions;
    }

    /**
     * @return array
     */
    private function installmentsCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_OPTIONS, Config::INSTALLMENT_OPTION);
    }

    /**
     * @return array
     */
    private function promotionsCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->terms(Elastic::FIELD_GOODS_LABELS_IDS, Filters::$filterPromotionTags);
    }

    /**
     * @inerhitDoc
     * @return array
     */
    public function getQuery(): array
    {
        $queries = [];
        $this->filters->goodsWithPromotions->hideValues();
        $this->typeCount = 'installment';
        $this->setCurrentFilterQuery($this->installmentsCustomFiltersConditions());
        $queries[Filters::PROMOTION_GOODS_INSTALLMENT] = $this->getDataQuery();

        $this->setCurrentFilterQuery($this->promotionsCustomFiltersConditions());
        $this->typeCount = 'promotion';
        $queries[Filters::PROMOTION_GOODS_PROMOTION] = $this->getDataQuery();
        $this->filters->goodsWithPromotions->showValues();

        return $queries;
    }

    /**
     * @param array $response
     * @return void
     */
    private function prepareAggData(array $response): void
    {
        if (\array_keys($response['aggregations'])[0] === 'installment_types_count') {
            $this->aggData[Filters::PROMOTION_GOODS_INSTALLMENT] = $this->elasticWrapper->prepareCountAggrData(
                $response,
                'installment_types_count'
            );
        }

        if (\array_keys($response['aggregations'])[0] === 'promotion_types_count') {
            $this->aggData[Filters::PROMOTION_GOODS_PROMOTION] = $this->elasticWrapper->prepareCountAggrData(
                $response,
                'promotion_types_count'
            );
        }
    }

    /**
     * @inerhitDoc
     * @param array $response
     * @return array
     */
    public function getValueFromMSearch(array $response): array
    {
        $this->prepareAggData($response);

        if (
            ($this->aggData[Filters::PROMOTION_GOODS_INSTALLMENT] === null
                || $this->aggData[Filters::PROMOTION_GOODS_PROMOTION] === null)
            || (!$this->aggData[Filters::PROMOTION_GOODS_INSTALLMENT]
                && !$this->aggData[Filters::PROMOTION_GOODS_PROMOTION])
        ) {
            return [];
        }

        $goodsWithPromotions = [];
        $order = 0;

        foreach ($this->aggData as $filter => $count) {
            if (!$count) {
                continue;
            }

            $option = [
                'option_value_id' => $filter,
                'option_value_name' => $filter,
                'option_value_title' => __('filters.' . $filter),
                'is_chosen' => false,
                'products_quantity' => $count,
                'order' => $order,
            ];

            $order++;

            // установка выбранных фильтров
            if ($this->filters->goodsWithPromotions->getValues()->contains($filter)) {
                $option['is_chosen'] = true;

                $this->chosen[Filters::PARAM_PROMOTION_GOODS][$option['option_value_name']] = [
                    'id' => $option['option_value_id'],
                    'name' => $option['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_PROMOTION_GOODS),
                    'option_value_title' => $option['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }

            $goodsWithPromotions[] = $option;
        }

        return [
            Filters::PARAM_PROMOTION_GOODS => [
                'option_id' => Filters::PARAM_PROMOTION_GOODS,
                'option_name' => Filters::PARAM_PROMOTION_GOODS,
                'option_title' => __('filters.' . Filters::PARAM_PROMOTION_GOODS),
                'option_type' => Filters::OPTION_TYPE_LIST,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($goodsWithPromotions),
                'option_values' => $goodsWithPromotions
            ]
        ];
    }
}
