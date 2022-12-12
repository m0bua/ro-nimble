<?php
/**
 * Класс для генерации параметра "size" (Количество элементов на странице)
 * Class SizeComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Config;
use App\Enums\Elastic;

class SizeComponent extends BaseComponent
{
    /**
     * @return float[]|int[]
     */
    public function getValue(): array
    {
        return [Elastic::PARAM_SIZE => $this->filters->perPage->getValues()->first()];
    }

    /**
     * @return array
     */
    public function getDefaultElasticSize(): array
    {
        return [Elastic::PARAM_SIZE => Config::ELASTIC_DEFAULT_SIZE];
    }

    /**
     * @return int[]
     */
    public function getZeroElasticSize(): array
    {
        return [Elastic::PARAM_SIZE => 0];
    }
}
