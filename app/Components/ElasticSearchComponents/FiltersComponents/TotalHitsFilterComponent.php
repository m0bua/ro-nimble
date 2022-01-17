<?php
/**
 * Класс для генерации параметра "track_total_hits" (точный подсчет всех результатов) при агрегации фильтров
 * Class TotalHitsFilterComponent
 * https://www.elastic.co/guide/en/elasticsearch/reference/master/search-your-data.html
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Elastic;

class TotalHitsFilterComponent extends BaseComponent
{
    /**
     * @return float[]|int[]
     */
    public function getValue(): array
    {
        return [Elastic::PARAM_TRACK_TOTAL_HITS => false];
    }
}
