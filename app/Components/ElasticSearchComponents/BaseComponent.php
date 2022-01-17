<?php
/**
 * Базовый класс компонентов для запросов в Elasticsearch
 * Class BaseComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;


use App\Filters\Filters;
use App\Helpers\ElasticWrapper;

abstract class BaseComponent
{
    /**
     * @var Filters
     */
    protected Filters $filters;

    /**
     * @var ElasticWrapper
     */
    protected ElasticWrapper $elasticWrapper;

    public function __construct(
        Filters $filters,
        ElasticWrapper $elasticWrapper
    ) {
        $this->filters = $filters;
        $this->elasticWrapper = $elasticWrapper;
    }

    abstract public function getValue(): array;
}
