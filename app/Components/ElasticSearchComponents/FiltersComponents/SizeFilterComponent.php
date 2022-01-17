<?php
/**
 * Класс для генерации параметра "size" (Количество элементов на странице) при агрегации фильтров
 * Class SizeComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class SizeFilterComponent extends BaseComponent
{
    /**
     * @return float[]|int[]
     */
    public function getValue(): array
    {
        return [Elastic::PARAM_SIZE => Config::ELASTIC_DEFAULT_FILTER_SIZE];
    }
}
