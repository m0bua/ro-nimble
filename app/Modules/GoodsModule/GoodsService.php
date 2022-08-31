<?php
/**
 * Class GoodsService
 * @package App\Modules\GoodsModule
 */
namespace App\Modules\GoodsModule;

use App\Components\ElasticSearchComponents\CategoryComponent;
use App\Components\ElasticSearchComponents\CollapseComponent;
use App\Components\ElasticSearchComponents\FromComponent;
use App\Components\ElasticSearchComponents\SingleGoodsComponent;
use App\Components\ElasticSearchComponents\SizeComponent;
use App\Components\ElasticSearchComponents\SortComponent;
use App\Components\ElasticSearchComponents\SourceComponent;
use App\Enums\Elastic;
use App\Filters\Filters;
use App\Helpers\ElasticWrapper;
use App\Models\Elastic\GoodsModel;
use App\Modules\ElasticModule\ElasticService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        CollapseComponent $collapseComponent
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
    }

    /**
     * @var array|string[]
     */
    private array $selectFields = [
        'id',
    ];

    public function getGoods(): array
    {
        if (!$this->filters->category->getValues() && !$this->filters->promotion->getValues()) {
            throw new BadRequestHttpException('Missing required parameters');
        }

        if (!$this->filters->singleGoods->isCheck()) {
            $queryBody = $this->elasticWrapper->terms(Elastic::FIELD_ID, $this->getFilteredGoods());
        } else {
            $queryBody = $this->elasticWrapper->bool(
                [
                    $this->elasticWrapper->filter(
                        $this->elasticService->getDefaultFiltersConditions()
                    ),
                    $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                ]
            );
        }

        if (!empty($queryBody)) {
            $data = $this->goodsModel->search(
                $this->elasticWrapper->body([
                    $this->fromComponent->getValue(),
                    $this->sizeComponent->getValue(),
                    $this->sortComponent->getValue(),
                    $this->sourceComponent->setFields($this->selectFields)->getValue(),
                    $this->elasticWrapper->query(
                        $queryBody
                    )
                ])
            );
        } else {
            $data = $this->elasticWrapper::EMPTY_SEARCH_RESULT;
        }

        if ($this->filters->promotion->getValues()->isNotEmpty()
                && $this->filters->category->getValues()->isEmpty()) {
            $goodsInCategory = $data['hits']['total']['value'];
        } else {
            $goodsInCategory = $this->goodsModel->search(
                $this->elasticWrapper->body(
                    $this->elasticWrapper->query(
                        $this->categoryComponent->getValue()
                    )
                )
            )['hits']['total']['value'];
        }

        $idsCount = $data['hits']['total']['value'];

        return [
            'ids' => $this->getIds($data['hits']['hits']),
            'ids_count' => $idsCount,
            'goods_in_category' => $goodsInCategory,
            'shown_page' => $this->filters->page->getValues()['min'],
            'goods_limit' => $this->filters->perPage->getValues()[0],
            'total_pages' => ceil($idsCount / $this->filters->perPage->getValues()[0]),
        ];
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
     * Соединяет в группы и внутри груп проводит сортировку
     * Отдает id товаров из поля inner_hits
     *
     * @return int[]
     */
    public function getFilteredGoods (): array
    {
        $data = $this->goodsModel->search(
            $this->elasticWrapper->body([
                $this->sourceComponent->setFields([Elastic::FIELD_GROUP_TOKEN])->getValue(),
                $this->elasticWrapper->query(
                    $this->elasticWrapper->bool(
                        [
                            $this->elasticWrapper->filter(
                                $this->elasticService->getDefaultFiltersConditions(),
                            ),
                            $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                        ]
                    )
                ),
                $this->collapseComponent->getCollapseForGoods($this->sortComponent->getValueForCollapse())
            ])
        );

        return $this->getIds($data['hits']['hits']);
    }
}
