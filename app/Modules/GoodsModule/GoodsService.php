<?php
/**
 * Class GoodsService
 * @package App\Modules\GoodsModule
 */
namespace App\Modules\GoodsModule;

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
     * @var SingleGoodsComponent
     */
    private SingleGoodsComponent $singleGoodsComponent;
    /**
     * @var CollapseComponent
     */
    private CollapseComponent $collapseComponent;

    public function __construct(
        ElasticService $elasticService,
        GoodsModel $goodsModel,
        Filters $filters,
        ElasticWrapper $elasticWrapper,

        FromComponent $fromComponent,
        SizeComponent $sizeComponent,
        SortComponent $sortComponent,
        SourceComponent $sourceComponent,
        SingleGoodsComponent $singleGoodsComponent,
        CollapseComponent $collapseComponent
    ) {
        $this->elasticService = $elasticService;
        $this->goodsModel = $goodsModel;
        $this->filters = $filters;
        $this->elasticWrapper = $elasticWrapper;

        $this->fromComponent = $fromComponent;
        $this->sizeComponent = $sizeComponent;
        $this->sortComponent = $sortComponent;
        $this->sourceComponent = $sourceComponent->setFields($this->selectFields);
        $this->singleGoodsComponent = $singleGoodsComponent;
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

        $data = $this->goodsModel->search(
            $this->elasticWrapper->body([
                $this->fromComponent->getValue(),
                $this->sizeComponent->getValue(),
                $this->sortComponent->getValue(),
                $this->sourceComponent->setFields($this->selectFields)->getValue(),
                $this->elasticWrapper->query(
                    $this->elasticWrapper->bool(
                        [
                            $this->elasticWrapper->filter(
                                array_merge(
                                    $this->elasticService->getDefaultFiltersConditions(),
                                    [$this->getSingleGoodsConditions()]
                                )
                            ),
                            $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                        ]
                    )
                ),
                $this->collapseComponent->getValue()
            ])
        );

        $idsCount = $data['hits']['total']['value'];

        return [
            'ids' => $this->getIds($data['hits']['hits']),
            'ids_count' => $idsCount,
            'shown_page' => $this->filters->page->getValues()['min'],
            'goods_limit' => $this->filters->perPage->getValues()[0],
            'total_pages' => ceil($idsCount / $this->filters->perPage->getValues()[0])
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
     * Генерит условие для вывода групповых товаров в акции
     * @return array
     */
    public function getSingleGoodsConditions()
    {
        if ($this->singleGoodsComponent->isCheck()) {
            $this->singleGoodsComponent->setExcludedGroups(
                $this->getExcludedGroups()
            );
        }

        return $this->singleGoodsComponent->getValue();
    }

    /**
     * Получает группы, для которых в выдаче, нет главных товаров
     * @return array
     */
    public function getExcludedGroups(): array
    {
        $data = $this->goodsModel->search(
            $this->elasticWrapper->body([
                $this->sizeComponent->getDefaultElasticSize(),
                $this->sourceComponent->setFields([Elastic::FIELD_GROUP_ID])->getValue(),
                $this->elasticWrapper->query(
                    $this->elasticWrapper->bool(
                        [
                            $this->elasticWrapper->filter(
                                array_merge(
                                    $this->elasticService->getDefaultFiltersConditions(),
                                    [$this->singleGoodsComponent->getPrimaryGroupConditions()]
                                )
                            ),
                            $this->elasticWrapper->mustNotSingle($this->elasticService->getExcludedCategories())
                        ]
                    )
                )
            ])
        );

        return $this->elasticWrapper->getUniqueFieldData($data, 'group_id');
    }
}
