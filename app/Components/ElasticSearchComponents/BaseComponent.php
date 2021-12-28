<?php
/**
 * Базовый класс компонентов для запросов в Elasticsearch
 * Class BaseComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;


use App\Helpers\ElasticWrapper;

abstract class BaseComponent
{
    /**
     * @var ElasticWrapper
     */
    protected ElasticWrapper $elasticWrapper;

    public function __construct(
        ElasticWrapper $elasticWrapper
    ) {
        $this->elasticWrapper = $elasticWrapper;
    }

    abstract public function getValue(): array;
}
