<?php
/**
 * Class ElasticService
 * @package App\Modules\ElasticModule
 */

namespace App\Modules\ElasticModule;

use App\Components\ElasticSearchComponents\BonusComponent;
use App\Components\ElasticSearchComponents\CategoriesComponent;
use App\Components\ElasticSearchComponents\CategoryComponent;
use App\Components\ElasticSearchComponents\CountryComponent;
use App\Components\ElasticSearchComponents\GoodsWithPromotionsComponent;
use App\Components\ElasticSearchComponents\MerchantComponent;
use App\Components\ElasticSearchComponents\OptionCheckedComponent;
use App\Components\ElasticSearchComponents\OptionsComponent;
use App\Components\ElasticSearchComponents\OptionSlidersComponent;
use App\Components\ElasticSearchComponents\OptionValuesComponent;
use App\Components\ElasticSearchComponents\PriceComponent;
use App\Components\ElasticSearchComponents\ProducerComponent;
use App\Components\ElasticSearchComponents\PromotionComponent;
use App\Components\ElasticSearchComponents\QueryComponent;
use App\Components\ElasticSearchComponents\SectionComponent;
use App\Components\ElasticSearchComponents\SellStatusComponent;
use App\Components\ElasticSearchComponents\SeriesComponent;
use App\Components\ElasticSearchComponents\StateComponent;
use App\Components\ElasticSearchComponents\StatusInheritedComponent;
use App\Helpers\ElasticWrapper;

class ElasticService
{
    /**
     * @var StatusInheritedComponent
     */
    private StatusInheritedComponent $statusInheritedComponent;
    /**
     * @var SellStatusComponent
     */
    private SellStatusComponent $sellStatusComponent;
    /**
     * @var PromotionComponent
     */
    private PromotionComponent $promotionComponent;
    /**
     * @var SectionComponent
     */
    private SectionComponent $sectionComponent;
    /**
     * @var ProducerComponent
     */
    private ProducerComponent $producerComponent;
    /**
     * @var PriceComponent
     */
    private PriceComponent $priceComponent;
    /**
     * @var MerchantComponent
     */
    private MerchantComponent $merchantComponent;
    /**
     * @var BonusComponent
     */
    private BonusComponent $bonusComponent;
    /**
     * @var StateComponent
     */
    private StateComponent $stateComponent;
    /**
     * @var SeriesComponent
     */
    private SeriesComponent $seriesComponent;
    /**
     * @var OptionCheckedComponent
     */
    private OptionCheckedComponent $optionCheckedComponent;
    /**
     * @var OptionsComponent
     */
    private OptionsComponent $optionsComponent;
    /**
     * @var OptionValuesComponent
     */
    private OptionValuesComponent $optionValuesComponent;
    /**
     * @var OptionSlidersComponent
     */
    private OptionSlidersComponent $optionSlidersComponent;
    /**
     * @var CategoryComponent
     */
    private CategoryComponent $categoryComponent;
    /**
     * @var CategoriesComponent
     */
    private CategoriesComponent $categoriesComponent;
    /**
     * @var CountryComponent
     */
    private CountryComponent $countryComponent;
    /**
     * @var GoodsWithPromotionsComponent
     */
    private GoodsWithPromotionsComponent $goodsWithPromotionsComponent;
    /**
     * @var QueryComponent
     */
    private QueryComponent $queryComponent;

    /**
     * @var ElasticWrapper
     */
    private ElasticWrapper $elasticWrapper;

    public function __construct(
        StatusInheritedComponent $statusInheritedComponent,
        SellStatusComponent $sellStatusComponent,
        PromotionComponent $promotionComponent,
        SectionComponent $sectionComponent,
        ProducerComponent $producerComponent,
        PriceComponent $priceComponent,
        MerchantComponent $merchantComponent,
        BonusComponent $bonusComponent,
        StateComponent $stateComponent,
        SeriesComponent $seriesComponent,
        OptionCheckedComponent $optionCheckedComponent,
        OptionsComponent $optionsComponent,
        OptionValuesComponent $optionValuesComponent,
        OptionSlidersComponent $optionSlidersComponent,
        CategoryComponent $categoryComponent,
        CategoriesComponent $categoriesComponent,
        CountryComponent $countryComponent,
        GoodsWithPromotionsComponent $goodsWithPromotionsComponent,
        QueryComponent $queryComponent,

        ElasticWrapper $elasticWrapper
    ) {
        $this->statusInheritedComponent = $statusInheritedComponent;
        $this->sellStatusComponent = $sellStatusComponent;
        $this->promotionComponent = $promotionComponent;
        $this->sectionComponent = $sectionComponent;
        $this->producerComponent = $producerComponent;
        $this->priceComponent = $priceComponent;
        $this->merchantComponent = $merchantComponent;
        $this->bonusComponent = $bonusComponent;
        $this->stateComponent = $stateComponent;
        $this->seriesComponent = $seriesComponent;
        $this->optionCheckedComponent = $optionCheckedComponent;
        $this->optionsComponent = $optionsComponent;
        $this->optionValuesComponent = $optionValuesComponent;
        $this->optionSlidersComponent = $optionSlidersComponent;
        $this->categoryComponent = $categoryComponent;
        $this->categoriesComponent = $categoriesComponent;
        $this->countryComponent = $countryComponent;
        $this->goodsWithPromotionsComponent = $goodsWithPromotionsComponent;
        $this->queryComponent = $queryComponent;

        $this->elasticWrapper = $elasticWrapper;
    }

    /**
     * Набор условий учитывающий текущие фильтра
     * @return mixed
     */
    public function getDefaultFiltersConditions()
    {
        return array_merge([
            $this->statusInheritedComponent->getValue(),
            $this->sellStatusComponent->getValue(),
            $this->promotionComponent->getValue(),
            $this->sectionComponent->getValue(),
            $this->producerComponent->getValue(),
            $this->priceComponent->getValue(),
            $this->merchantComponent->getValue(),
            $this->bonusComponent->getValue(),
            $this->stateComponent->getValue(),
            $this->seriesComponent->getValue(),
            $this->optionCheckedComponent->getValue(),
            $this->categoryComponent->getValue(),
            $this->categoriesComponent->getValue(),
            $this->countryComponent->getValue(),
            $this->queryComponent->getValue(),
        ],
            $this->optionsComponent->getValue(),
            $this->optionValuesComponent->getValue(),
            $this->optionSlidersComponent->getValue(),
            $this->goodsWithPromotionsComponent->getValue()
        );
    }

    public function getExcludedCategories(): array {
        return $this->categoryComponent->isExcludedCategoryExists()
            ? $this->categoryComponent->getExcludedValue()
            : [];
    }
}
