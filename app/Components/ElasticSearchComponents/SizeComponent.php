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

    public function getDefaultElasticSize()
    {
        return [Elastic::PARAM_SIZE => Config::ELASTIC_DEFAULT_SIZE];
    }
}
