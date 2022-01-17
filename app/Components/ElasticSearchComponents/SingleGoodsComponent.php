<?php
/**
 * Класс для генерации параметра "producer_id"
 * Class SingleGoodsComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class SingleGoodsComponent extends BaseComponent
{
    /**
     * Группы, для которых в выдаче, нет главных товаров
     * @var array
     */
    private $excludedGroups = [];

    /**
     * @return array
     */
    public function getValue(): array
    {
        if ($this->isCheck()) {
            return $this->elasticWrapper->bool(
                $this->elasticWrapper->should([
                    $this->elasticWrapper->bool(
                        $this->elasticWrapper->filter([
                            $this->elasticWrapper->term(Elastic::FIELD_GROUP_ID, 0),
                            $this->elasticWrapper->term(Elastic::FIELD_IS_GROUP_PRIMARY, 0)
                        ])
                    ),
                    $this->elasticWrapper->term(Elastic::FIELD_IS_GROUP_PRIMARY, 1),
                    $this->getExcludedGroupsConditions(),
                ])
            );
        }

        return $this->elasticWrapper::DEFAULT_RESULT;
    }

    /**
     * @return array[]
     */
    public function getExcludedGroupsConditions()
    {
        if (!$this->excludedGroups) {
            return $this->elasticWrapper::DEFAULT_RESULT;
        }

        return $this->elasticWrapper->bool([
            $this->elasticWrapper->mustNot([
                $this->elasticWrapper->terms(Elastic::FIELD_GROUP_ID, $this->excludedGroups)
            ]),
            $this->elasticWrapper->filter([
                $this->elasticWrapper->term(Elastic::FIELD_IS_GROUP_PRIMARY, 0)
            ])
        ]);
    }

    /**
     * @return bool
     */
    public function isCheck(): bool
    {
        return $this->filters->promotion->getValues() && !$this->filters->singleGoods->isCheck();
    }

    /**
     * @param array $excludedGroups
     */
    public function setExcludedGroups(array $excludedGroups)
    {
        $this->excludedGroups = $excludedGroups;
    }

    public function getPrimaryGroupConditions(): array
    {
        return $this->elasticWrapper->bool(
            $this->elasticWrapper->filter([
                $this->elasticWrapper->term(Elastic::FIELD_IS_GROUP_PRIMARY, 1),
                $this->elasticWrapper->range(Elastic::FIELD_GROUP_ID, [
                    $this->elasticWrapper::RANGE_GT => 0
                ])
            ])
        );
    }
}
