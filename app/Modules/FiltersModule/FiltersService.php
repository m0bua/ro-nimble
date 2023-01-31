<?php
/**
 * Class FiltersService
 * @package App\Modules\FiltersModule
 */

namespace App\Modules\FiltersModule;

use App\Enums\Filters as EnumsFilters;
use App\Enums\Resources;
use App\Filters\Filters;
use App\Models\Elastic\GoodsModel;
use App\Modules\FiltersModule\Components\BonusService;
use App\Modules\FiltersModule\Components\CategoriesService;
use App\Modules\FiltersModule\Components\GoodsWithPromotionsService;
use App\Modules\FiltersModule\Components\OptionsService;
use App\Modules\FiltersModule\Components\PaymentService;
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

    /**
     * @var PaymentService
     */
    private PaymentService $payments;

    /**
     * @var GoodsModel
     */
    protected GoodsModel $goodsModel;

    public function __construct(
        Filters                    $filters,
        OrderService               $orderService,

        SectionService             $sectionService,
        CategoriesService          $categoriesService,
        SellerService              $sellerService,
        ProducerService            $producerService,
        SeriesService              $seriesService,
        PriceService               $priceService,
        GoodsWithPromotionsService $goodsWithPromotionsService,
        BonusService               $bonusService,
        StateService               $stateService,
        SellStatusService          $sellStatusService,
        OptionsService             $optionsService,
        PaymentService             $paymentsService,
        GoodsModel                 $goodsModel
    )
    {
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
        $this->payments = $paymentsService;
        $this->goodsModel = $goodsModel;
    }

    /**
     * Возвращает набор фильтров
     * @return array
     * @throws BadRequestHttpException
     */
    public function getFilters(): array
    {
        if ($this->filters->category->getValues()->isEmpty() && $this->filters->promotion->getValues()->isEmpty()) {
            throw new BadRequestHttpException(
                'Missing required parameters. At least category_id or promotion_id must be used.'
            );
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
     * @noinspection ClosureToArrowFunctionInspection
     */
    public function prepareOptions(Collection $options): array
    {
        return $options
            ->groupBy(function ($option) {
                switch (true) {
                    case in_array($option['option_name'], EnumsFilters::$staticFiltersParams, false):
                        return Resources::OPTIONS_SPECIFIC;
                    case $option->has('option_values'):
                        return Resources::OPTIONS_GENERAL;
                    default:
                        return Resources::OPTIONS_SLIDERS;
                }
            })
            ->map(function (Collection $group, string $key) {
                if ($key === Resources::OPTIONS_SPECIFIC) {
                    return $group->keyBy('option_name');
                }

                return $group;
            })
            ->toArray();
    }

    /**
     * Возвращает набор кастомных фильтров
     * @return Collection
     */
    public function getOptions(): Collection
    {
        $params = ['body' => []];
        $index = ['index' => $this->goodsModel->getIndexName()];
        $aggIndex = [];
        $models = [
            $this->sectionService,
            $this->categoriesService,
            $this->sellerService,
            $this->producerService,
            $this->seriesService,
            $this->priceService,
            $this->goodsWithPromotionsService,
            $this->bonusService,
            $this->stateService,
            $this->sellStatusService,
            $this->payments,
            $this->optionsService
        ];

        foreach ($models as $model) {
            $queries = $model->getQuery();
            if (empty($queries)) {
                continue;
            }
            foreach ($queries as $query) {
                $params['body'][] = $index;
                $params['body'][] = $query;
                $aggIndex[] = $model;
            }
        }

        unset($models);
        $rawData = $this->goodsModel->getClient()->msearch($params);
        $data = [];
        foreach ($rawData['responses'] as $index => $response) {
            $data = \array_merge($data, $aggIndex[$index]->getValueFromMSearch($response));
        }

        return collect($data)->values()->recursive();
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
                $this->sellStatusService->getChosen(),
                $this->payments->getChosen(),
            ) + $this->optionsService->getChosen();
    }

    /**
     * Возвращает найденные бренды для фильтра "Продюсер"
     * @return array
     * @throws BadRequestHttpException
     */
    public function searchBrands(): array
    {
        if ($this->filters->category->getValues()->isEmpty()
            || $this->filters->query->getValues()->isEmpty()
        ) {
            throw new BadRequestHttpException(
                'Missing required parameters. Please existing category and query parameter.'
            );
        }

        $this->filters->category->disableAutoranking();

        return [
            Resources::OPTIONS => $this->orderService->orderOptions($this->producerService->searchBrands())->toArray()
        ];
    }
}
