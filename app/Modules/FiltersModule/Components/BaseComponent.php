<?php
/**
 * Базовый класс сервисов для фильтров
 * Class BaseComponent
 * @package App\Components\ElasticSearchFiltersComponents
 */

namespace App\Modules\FiltersModule\Components;

use App\Components\ElasticSearchComponents\FiltersComponents\CategoriesFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\CountFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\OptionCheckedFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\OptionsFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\OptionSlidersFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\OptionValuesFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\PaymentsFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\PriceFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\ProducerFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\SectionFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\SellerFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\SellStatusFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\SeriesFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\SizeFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\StateFilterComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\TotalHitsFilterComponent;
use App\Filters\Filters;
use App\Helpers\ElasticWrapper;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Option;
use App\Modules\ElasticModule\ElasticService;

abstract class BaseComponent
{
    /**
     * @var array
     */
    protected array $chosen = [];

    /**
     * @var ElasticService
     */
    protected ElasticService $elasticService;
    /**
     * @var GoodsModel
     */
    protected GoodsModel $goodsModel;
    /**
     * @var Filters
     */
    protected Filters $filters;
    /**
     * @var ElasticWrapper
     */
    protected ElasticWrapper $elasticWrapper;
    /**
     * @var Option
     */
    protected Option $option;

    /**
     * @var SizeFilterComponent
     */
    protected SizeFilterComponent $sizeFilterComponent;
    /**
     * @var TotalHitsFilterComponent
     */
    protected TotalHitsFilterComponent $totalHitsFilterComponent;
    /**
     * @var PriceFilterComponent
     */
    protected PriceFilterComponent $priceFilterComponent;
    /**
     * @var ProducerFilterComponent
     */
    protected ProducerFilterComponent $producerFilterComponent;
    /**
     * @var SellerFilterComponent
     */
    protected SellerFilterComponent $sellerFilterComponent;
    /**
     * @var SellStatusFilterComponent
     */
    protected SellStatusFilterComponent $sellStatusFilterComponent;
    /**
     * @var StateFilterComponent
     */
    protected StateFilterComponent $stateFilterComponent;
    /**
     * @var CountFilterComponent
     */
    protected CountFilterComponent $countFilterComponent;
    /**
     * @var SeriesFilterComponent
     */
    protected SeriesFilterComponent $seriesFilterComponent;
    /**
     * @var CategoriesFilterComponent
     */
    protected CategoriesFilterComponent $categoriesFilterComponent;
    /**
     * @var SectionFilterComponent
     */
    protected SectionFilterComponent $sectionFilterComponent;
    /**
     * @var OptionsFilterComponent
     */
    protected OptionsFilterComponent $optionsFilterComponent;
    /**
     * @var OptionValuesFilterComponent
     */
    protected OptionValuesFilterComponent $optionValuesFilterComponent;
    /**
     * @var OptionCheckedFilterComponent
     */
    protected OptionCheckedFilterComponent $optionCheckedFilterComponent;
    /**
     * @var OptionSlidersFilterComponent
     */
    protected OptionSlidersFilterComponent $optionSlidersFilterComponent;

    /**
     * @var PaymentsFilterComponent
     */
    protected PaymentsFilterComponent $paymentsFilterComponent;

    public function __construct(
        ElasticService $elasticService,
        GoodsModel $goodsModel,
        Filters $filters,
        ElasticWrapper $elasticWrapper,
        Option $option,

        SizeFilterComponent $sizeFilterComponent,
        TotalHitsFilterComponent $totalHitsFilterComponent,

        PriceFilterComponent $priceFilterComponent,
        ProducerFilterComponent $producerFilterComponent,
        SellerFilterComponent $sellerFilterComponent,
        SellStatusFilterComponent $sellStatusFilterComponent,
        StateFilterComponent $stateFilterComponent,
        CountFilterComponent $countFilterComponent,
        SeriesFilterComponent $seriesFilterComponent,
        CategoriesFilterComponent $categoriesFilterComponent,
        SectionFilterComponent $sectionFilterComponent,
        OptionsFilterComponent $optionsFilterComponent,
        OptionValuesFilterComponent $optionValuesFilterComponent,
        OptionCheckedFilterComponent $optionCheckedFilterComponent,
        OptionSlidersFilterComponent $optionSlidersFilterComponent,
        PaymentsFilterComponent $paymentMethodsFilterComponent
    ) {
        $this->elasticService = $elasticService;
        $this->goodsModel = $goodsModel;
        $this->filters = $filters;
        $this->elasticWrapper = $elasticWrapper;
        $this->option = $option;

        $this->sizeFilterComponent = $sizeFilterComponent;
        $this->totalHitsFilterComponent = $totalHitsFilterComponent;

        $this->priceFilterComponent = $priceFilterComponent;
        $this->producerFilterComponent = $producerFilterComponent;
        $this->sellerFilterComponent = $sellerFilterComponent;
        $this->sellStatusFilterComponent = $sellStatusFilterComponent;
        $this->stateFilterComponent = $stateFilterComponent;
        $this->countFilterComponent = $countFilterComponent;
        $this->seriesFilterComponent = $seriesFilterComponent;
        $this->categoriesFilterComponent = $categoriesFilterComponent;
        $this->sectionFilterComponent = $sectionFilterComponent;
        $this->optionsFilterComponent = $optionsFilterComponent;
        $this->optionValuesFilterComponent = $optionValuesFilterComponent;
        $this->optionCheckedFilterComponent = $optionCheckedFilterComponent;
        $this->optionSlidersFilterComponent = $optionSlidersFilterComponent;
        $this->paymentsFilterComponent = $paymentMethodsFilterComponent;
    }

    /**
     * Возвращает выбранные значение фильтра
     * @return array
     */
    public function getChosen(): array
    {
        return $this->chosen;
    }

    /**
     * Получение данных для фильтра
     * @return array
     */
    protected function getData(): array
    {
        return $this->goodsModel->search(
            $this->elasticWrapper->body([
                $this->sizeFilterComponent->getValue(),
                $this->totalHitsFilterComponent->getValue(),
                $this->getFilterQuery(),
                $this->elasticWrapper->query(
                    $this->elasticWrapper->bool(
                        [
                            $this->elasticWrapper->filter(
                                array_merge(
                                    [$this->getCustomFiltersConditions()],
                                    $this->elasticService->getDefaultFiltersConditions()
                                )
                            ),
                            $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                        ]
                    )
                )
            ])
        );
    }

    /**
     * Возвращает дополнительные условия для запроса
     * @return array
     */
    public function getCustomFiltersConditions(): array
    {
        return [];
    }

    /**
     * Возвращает значение фильтра
     * @return array
     */
    abstract public function getValue(): array;

    /**
     * Возвращает основные параметры для запроса
     * @return array
     */
    abstract public function getFilterQuery(): array;
}
