<?php
/**
 * Класс для генерации параметра "from" (Точка старта выборки)
 * Class FromComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;


class FromComponent extends BaseComponent
{
    public const PARAM_NAME = 'from';

    public const DEFAULT_PARAM = 0;

    /**
     * @return float[]|int[]
     */
    public function getValue(): array
    {
        $page = $this->filters->page->getValues()[$this->filters->page::PAGE_MIN_KEY];
        $perPage = $this->filters->perPage->getValues()->first();

        return [self::PARAM_NAME => $page > 1 ? ($page - 1) * $perPage : self::DEFAULT_PARAM];
    }
}
