<?php
/**
 * Class FiltersService
 * @package App\Modules\FiltersModule
 */

namespace App\Modules\FiltersModule;

use App\Enums\Filters as EnumsFilters;
use App\Enums\Resources;
use App\Filters\Filters;
use App\Modules\FiltersModule\Components\BonusService;
use App\Modules\FiltersModule\Components\CategoriesService;
use App\Modules\FiltersModule\Components\GoodsWithPromotionsService;
use App\Modules\FiltersModule\Components\OptionsService;
use App\Modules\FiltersModule\Components\PriceService;
use App\Modules\FiltersModule\Components\ProducerService;
use App\Modules\FiltersModule\Components\SectionService;
use App\Modules\FiltersModule\Components\SellerService;
use App\Modules\FiltersModule\Components\SellStatusService;
use App\Modules\FiltersModule\Components\SeriesService;
use App\Modules\FiltersModule\Components\StateService;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FiltersService
{
    /**
     * @var Filters
     */
    private Filters $filters;

    /**
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * @var SectionService
     */
    private SectionService $sectionService;

    /**
     * @var CategoriesService
     */
    private CategoriesService $categoriesService;

    /**
     * @var SellerService
     */
    private SellerService $sellerService;

    /**
     * @var ProducerService
     */
    private ProducerService $producerService;

    /**
     * @var SeriesService
     */
    private SeriesService $seriesService;

    /**
     * @var PriceService
     */
    private PriceService $priceService;

    /**
     * @var GoodsWithPromotionsService
     */
    private GoodsWithPromotionsService $goodsWithPromotionsService;

    /**
     * @var BonusService
     */
    private BonusService $bonusService;

    /**
     * @var StateService
     */
    private StateService $stateService;

    /**
     * @var SellStatusService
     */
    private SellStatusService $sellStatusService;

    /**
     * @var OptionsService
     */
    private OptionsService $optionsService;

    public function __construct(
        Filters $filters,
        OrderService $orderService,

        SectionService $sectionService,
        CategoriesService $categoriesService,
        SellerService $sellerService,
        ProducerService $producerService,
        SeriesService $seriesService,
        PriceService $priceService,
        GoodsWithPromotionsService $goodsWithPromotionsService,
        BonusService $bonusService,
        StateService $stateService,
        SellStatusService $sellStatusService,
        OptionsService $optionsService
    ) {
        $this->filters = $filters;
        $this->orderService = $orderService;

        $this->sectionService = $sectionService;
        $this->categoriesService = $categoriesService;
        $this->sellerService = $sellerService;
        $this->producerService = $producerService;
        $this->seriesService = $seriesService;
        $this->priceService = $priceService;
        $this->goodsWithPromotionsService = $goodsWithPromotionsService;
        $this->bonusService = $bonusService;
        $this->stateService = $stateService;
        $this->sellStatusService = $sellStatusService;
        $this->optionsService = $optionsService;
    }

    /**
     * Возвращает набор фильтров
     * @return \array[][]
     * @throws ApiException
     */
    public function getFilters(): array
    {
        if ($this->filters->category->getValues()->isEmpty() && $this->filters->promotion->getValues()->isEmpty()) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        return [
            Resources::OPTIONS => $this->prepareOptions($this->orderService->orderOptions($this->getOptions())),
            Resources::CHOSEN => $this->getChosenFilters()
        ];
    }

    /**
     * Подготовка параметра option
     * @param Collection $options
     * @return array
     */
    public function prepareOptions(Collection $options): array
    {
        return $options->groupBy(function ($option) {
            switch (true) {
                case in_array($option['option_name'], EnumsFilters::$staticFiltersParams):
                    return Resources::OPTIONS_SPECIFIC;
                case $option->has('option_values'):
                    return Resources::OPTIONS_GENERAL;
                default:
                    return Resources::OPTIONS_SLIDERS;
            }
        })->map(function (Collection $group, string $key) {
            if ($key === Resources::OPTIONS_SPECIFIC) {
                return $group->keyBy('option_name');
            }

            return $group;
        })
        ->toArray();
    }

    /**
     * Возвращает набор кастомных фильтров
     * @return array
     */
    public function getOptions(): Collection
    {
        return collect(array_merge(
                $this->sectionService->getValue(),
                $this->categoriesService->getValue(),
                $this->sellerService->getValue(),
                $this->producerService->getValue(),
                $this->seriesService->getValue(),
                $this->priceService->getValue(),
                $this->goodsWithPromotionsService->getValue(),
                $this->bonusService->getValue(),
                $this->stateService->getValue(),
                $this->sellStatusService->getValue()
            ) + $this->optionsService->getValue()
        )->values()->recursive();
    }

    /**
     * Возвращает набор выбранных фильтров
     * @return array
     */
    public function getChosenFilters(): array
    {
        return array_merge(
                $this->sectionService->getChosen(),
                $this->sellerService->getChosen(),
                $this->categoriesService->getChosen(),
                $this->priceService->getChosen(),
                $this->producerService->getChosen(),
                $this->seriesService->getChosen(),
                $this->goodsWithPromotionsService->getChosen(),
                $this->bonusService->getChosen(),
                $this->stateService->getChosen(),
                $this->sellStatusService->getChosen()
            ) + $this->optionsService->getChosen();
    }

    /**
     * Возвращает найденные бренды для фильтра "Продюсер"
     * @return array
     */
    public function searchBrands(): array
    {
        if ($this->filters->category->getValues()->isEmpty()
            || $this->filters->query->getValues()->isEmpty()
        ) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        $this->filters->category->disableAutoranking();

        return [
            Resources::OPTIONS => $this->producerService->searchBrands()
        ];
    }
}
