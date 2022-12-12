<?php
/**
 * Класс для генерации параметра "collapse" (группировка по полю)
 * Class CollapseComponent
 * @package App\Components\ElasticSearchComponents
 * https://www.elastic.co/guide/en/elasticsearch/reference/7.13/collapse-search-results.html
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;
use App\Enums\Filters;

class CollapseComponent extends BaseComponent
{
    public const PARAM_NAME = 'collapse';

    /**
     * @return float[]|int[]
     */
    public function getValue(): array
    {
        if ($this->filters->promotion->getValues()) {
            return $this->elasticWrapper::DEFAULT_RESULT;
        }

        $params = ['field' => Elastic::FIELD_GROUP_TOKEN];

        if (!in_array($this->filters->sort->getValue(), [Filters::SORT_CHEAP, Filters::SORT_EXPENSIVE])) {
            $params['inner_hits'] = [
                'name' => 'group',
                'size' => 1,
                'sort' => [[
                    'is_group_primary' => 'desc',
                    'rank' => 'desc'
                ]]
            ];
        }

        return [self::PARAM_NAME => $params];
    }

    /**
     * @param array $sort
     * @param bool $withInnerHits
     *
     * @return array[]
     */
    public function getCollapseForGoods(array $sort = [], bool $withInnerHits = false): array
    {
        $params = ['field' => Elastic::FIELD_GROUP_TOKEN];

        if ($withInnerHits) {
            $params['inner_hits'] = [
                'name' => 'group',
                '_source' => 'id',
                'size' => 1
            ];

            $params['inner_hits'] = array_merge($params['inner_hits'], $sort);
        }

        return [self::PARAM_NAME => $params];
    }
}
