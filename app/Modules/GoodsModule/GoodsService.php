<?php
/**
 * Class GoodsService
 * @package App\Modules\GoodsModule
 */
namespace App\Modules\GoodsModule;

use App\Components\ElasticSearchComponents\CategoryComponent;
use App\Components\ElasticSearchComponents\CollapseComponent;
use App\Components\ElasticSearchComponents\FiltersComponents\TotalHitsFilterComponent;
use App\Components\ElasticSearchComponents\FromComponent;
use App\Components\ElasticSearchComponents\SizeComponent;
use App\Components\ElasticSearchComponents\SortComponent;
use App\Components\ElasticSearchComponents\SourceComponent;
use App\Enums\Elastic;
use App\Filters\Components\Options\AbstractOptionFilter;
use App\Filters\Filters;
use App\Helpers\ElasticWrapper;
use App\Models\Elastic\GoodsModel;
use App\Modules\ElasticModule\ElasticService;
use Exception;
use Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GoodsService
{
    /**
     * @var ElasticService
     */
    private ElasticService $elasticService;
    /**
     * @var GoodsModel
     */
    private GoodsModel $goodsModel;
    /**
     * @var Filters
     */
    private Filters $filters;
    /**
     * @var ElasticWrapper
     */
    private ElasticWrapper $elasticWrapper;
    /**
     * @var CategoryComponent
     */
    private CategoryComponent $categoryComponent;
    /**
     * @var FromComponent
     */
    private FromComponent $fromComponent;
    /**
     * @var SizeComponent
     */
    private SizeComponent $sizeComponent;
    /**
     * @var SortComponent
     */
    private SortComponent $sortComponent;
    /**
     * @var SourceComponent
     */
    private SourceComponent $sourceComponent;
    /**
     * @var CollapseComponent
     */
    private CollapseComponent $collapseComponent;

    /**
     * @var TotalHitsFilterComponent
     */
    private TotalHitsFilterComponent $totalHitsComponent;

    /**
     * @var int
     */
    private int $countIn = 0;

    /**
     * @var bool
     */
    private bool $isPromotion = false;

    public function __construct(
        ElasticService $elasticService,
        GoodsModel $goodsModel,
        Filters $filters,
        ElasticWrapper $elasticWrapper,

        CategoryComponent $categoryComponent,
        FromComponent $fromComponent,
        SizeComponent $sizeComponent,
        SortComponent $sortComponent,
        SourceComponent $sourceComponent,
        CollapseComponent $collapseComponent,
        TotalHitsFilterComponent $totalHitsComponent
    ) {
        $this->elasticService = $elasticService;
        $this->goodsModel = $goodsModel;
        $this->filters = $filters;
        $this->elasticWrapper = $elasticWrapper;

        $this->categoryComponent = $categoryComponent;
        $this->fromComponent = $fromComponent;
        $this->sizeComponent = $sizeComponent;
        $this->sortComponent = $sortComponent;
        $this->sourceComponent = $sourceComponent->setFields($this->selectFields);
        $this->collapseComponent = $collapseComponent;
        $this->totalHitsComponent = $totalHitsComponent;
        $this->isPromotion = $this->filters->promotion->getValues()->isNotEmpty() && $this->filters->category->getValues()->isEmpty();
    }

    /**
     * @var array|string[]
     */
    private array $selectFields = [
        'id',
    ];

    /**
     * @return array
     * @throws Exception
     */
    public function getGoods(): array
    {
        try {
            if (!$this->filters->category->getValues() && !$this->filters->promotion->getValues()) {
                throw new BadRequestHttpException('Missing required parameters');
            }

            foreach ($this->filters->options as $item) {
                /** @var $item AbstractOptionFilter */
                $item->removeBlockedBySettings(\array_merge([0], $this->filters->category->getValues()->toArray()));
            }

            $singleGoods = $this->filters->singleGoods->isCheck();
            if (!$singleGoods) {
                $data = $this->getFilteredGroupedGoods();
                $data = $this->sortComponent->currentSortComponent->sortResultArray($data, $this->isPromotion);
            } else {
                $data = $this->getFilteredSingleGoods();
            }

            $ids = [];
            $idsCount = 0;
            if (!empty($data)) {
                if(!$singleGoods) {
                    $idsCount = count($data);

                    $data = array_column($data, 'id');

                    $chunkedArray = array_chunk(
                        $data,
                        $this->filters->perPage->getValues()->first()
                    );
                    if (!empty($chunkedArray)) {
                        $ids = $chunkedArray[$this->filters->page->getValues()->first() - 1];
                    } else {
                        $ids = [];
                    }

                } else {
                    $idsCount = $data['hits']['total']['value'];
                    $ids = $this->getIds($data['hits']['hits']);
                }
            }

            return [
                'ids' => $ids,
                'ids_count' => $idsCount,
                'goods_in_category' => $this->countIn,
                'shown_page' => $this->filters->page->getValues()['min'],
                'goods_limit' => $this->filters->perPage->getValues()[0],
                'total_pages' => ceil($idsCount / $this->filters->perPage->getValues()[0]),
            ];
        } catch (Exception $e) {
            $message = 'Something goes wrong.';
            Log::channel('api_errors')->error(
                $message,
                ['message' => $e->getMessage(), "class" => self::class .':'. $e->getLine()]
            );
            throw new HttpException(500, $message);
        }
    }

    /**
     * @param array $hits
     * @return array
     */
    public function getIds(array $hits): array
    {
        $ids = [];

        foreach ($hits as $hit) {
            $ids[] = $hit['inner_hits']['group']['hits']['hits'][0]['_source']['id'] ?? $hit['_source']['id'];
        }

        return $ids;
    }

    /**
     * Отфильтровывает результаты,
     * Внутри груп проводит сортировку и выбирает главного
     * Отдает массив товаров вида [0 => ['id'=>1, 'order' => 1, 'rank' => 'price', 'weight_sort'=> 1]]
     *
     * @return int[]
     */
    public function getFilteredGroupedGoods (): array
    {
        $first = $this->elasticWrapper->prepareMultiParams([
            $this->sizeComponent->getDefaultElasticSize(),
            $this->sourceComponent->setFields([
                Elastic::FIELD_ID,
                Elastic::FIELD_GROUP_TOKEN,
                Elastic::FIELD_ORDER,
                Elastic::FIELD_RANK,
                Elastic::FIELD_PRICE
            ])->getValue(),
            $this->sortComponent->getValue(),
            $this->elasticWrapper->query(
                $this->elasticWrapper->bool(
                    [
                        $this->elasticWrapper->filter(
                            array_merge(
                                $this->elasticService->getDefaultFiltersConditions(),
                                [["term" => ["is_group_primary" => 1]]]
                            )
                        ),
                        $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                    ]
                )
            ),
            $this->collapseComponent->getCollapseForGoods()
        ]);

        $second = $this->elasticWrapper->prepareMultiParams([
            $this->sizeComponent->getDefaultElasticSize(),
            $this->sourceComponent->setFields([
                Elastic::FIELD_ID,
                Elastic::FIELD_GROUP_TOKEN,
                Elastic::FIELD_ORDER,
                Elastic::FIELD_RANK,
                Elastic::FIELD_PRICE
            ])->getValue(),
            $this->sortComponent->getValue(),
            $this->elasticWrapper->query(
                $this->elasticWrapper->bool(
                    [
                        $this->elasticWrapper->filter(
                            array_merge(
                                $this->elasticService->getDefaultFiltersConditions(),
                                [["term" => ["is_group_primary" => 0]]]
                            )
                        ),
                        $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                    ]
                )
            ),
            $this->collapseComponent->getCollapseForGoods()
        ]);

        $index = ['index' => $this->goodsModel->getIndexName()];

        $params = [
            'body' => [
                $index,
                $first,
                $index,
                $second
            ]
        ];

        $third = $this->getCount();
        if (!empty($third)) {
            $params['body'][] = $index;
            $params['body'][] = $third;
        }

        $data = $this->sendMultiSearchRequest($params);

        // $data['response'][2] содержит общее кол-во товаров в категории
        if (isset($data['responses'][2]['hits']['total']['value'])) {
            $this->countIn = $data['responses'][2]['hits']['total']['value'];
        }

        $responsePrimary = [];
        $responseNotPrimary = [];

        // $data['response'][0] содержит товары в категории у которых is_primary_group = 1
        if (!empty($data['responses'][0]['hits']['hits'])){
            $responsePrimary = $this->createGoodsArray($data['responses'][0]['hits']['hits'], $this->isPromotion);
        }

        // $data['response'][1] содержит товары в категории у которых is_primary_group = 0
        if(!empty($data['responses'][1]['hits']['hits'])) {
            $responseNotPrimary = $this->createGoodsArray($data['responses'][1]['hits']['hits'], $this->isPromotion);
        }

        $resultArray = [];

        if (!empty($responseNotPrimary)) {
            foreach ($responseNotPrimary as $key => $item) {
                if (isset($responsePrimary[$key])) {
                    $resultArray[] = $this->sortComponent->currentSortComponent->getMainProductBySort($responsePrimary[$key], $item);
                    unset($responsePrimary[$key]);
                } else {
                    $resultArray[] = $item;
                }
            }
        } else {
            $resultArray = $responsePrimary;
        }

        if (!empty($responsePrimary)) {
            $resultArray = array_merge($resultArray, $responsePrimary);
        }

        return $resultArray;
    }

    private function createGoodsArray(array $data, bool $isPromotion = false): array
    {
        $result = [];

        foreach ($data as $item) {
            $result[$item['_source']['group_token']] = [
                'id' => $item['_source']['id'],
                'order' => $item['_source']['order'],
                'rank' => $item['_source']['rank'],
                'price' => $item['_source']['price'],
                'weight_sort' => $item['sort'][0],
                'promotion_order' => $isPromotion ? $item['sort'][2] : 0
            ];
        }

        return $result;
    }

    /**
     * Отфильтровывает результаты,
     * Внутри груп проводит сортировку и выбирает главного
     * Отдает массив id товаров вида [0 => '1', 1 => '3123', 2 => '321344'....]
     *
     *
     * @return array
     */
    public function getFilteredSingleGoods(): array
    {
        $resultArray = [];
        $index = ['index' => $this->goodsModel->getIndexName()];

        $first = $this->elasticWrapper->prepareMultiParams([
            $this->fromComponent->getValue(),
            $this->sizeComponent->getValue(),
            $this->sortComponent->getValue(),
            $this->totalHitsComponent->getTrueValue(),
            $this->sourceComponent->setFields($this->selectFields)->getValue(),
            $this->elasticWrapper->query(
                $this->elasticWrapper->bool(
                    [
                        $this->elasticWrapper->filter(
                            $this->elasticService->getDefaultFiltersConditions()
                        ),
                        $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                    ]
                )
            )
        ]);

        $params['body'][] = $index;
        $params['body'][] = $first;

        $second = $this->getCount();
        if (!empty($second)) {
            $params['body'][] = $index;
            $params['body'][] = $second;
        }

        $data = $this->sendMultiSearchRequest($params);

        if (isset($data['responses'][1]['hits']['total']['value'])) {
            $this->countIn = $data['responses'][1]['hits']['total']['value'];
        }

        if (!empty($data['responses'][0]['hits']['hits'])) {
            $resultArray = $data['responses'][0];
        }

        return $resultArray;
    }

    /**
     * @param $params
     * @return array
     */
    private function sendMultiSearchRequest($params): array
    {
        $client = $this->goodsModel->getClient();

        return $client->msearch($params);
    }

    /**
     * Проверяет источник
     * Для каждого источника создает свой запрос для вычесления общего кол-ва
     *
     * @return array
     */
    private function getCount(): array
    {
        if ($this->filters->promotion->getValues()->isEmpty() && $this->filters->category->getValues()->isNotEmpty()) {
            $data = $this->elasticWrapper->prepareMultiParams([
                $this->sizeComponent->getZeroElasticSize(),
                $this->totalHitsComponent->getTrueValue(),
                $this->elasticWrapper->query(
                    $this->categoryComponent->getValue()
                )
            ]);
        } else {
            $data = $this->elasticWrapper->prepareMultiParams([
                $this->sizeComponent->getZeroElasticSize(),
                $this->totalHitsComponent->getTrueValue(),
                $this->elasticWrapper->query(
                    $this->elasticWrapper->bool(
                        [
                            $this->elasticWrapper->filter(
                                $this->elasticService->getDefaultFiltersConditions(),
                            ),
                            $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                        ]
                    )
                )
            ]);
        }

        return $data;
    }
}
