<?php
/**
 * Класс для генерации параметра "sort" (Сортировка)
 * Class SortComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Components\ElasticSearchComponents\SortComponents\BaseSortComponent;

class SortComponent extends BaseComponent
{
    public const PARAM_NAME = 'sort';

    /**
     * @var BaseSortComponent
     */
    public BaseSortComponent $currentSortComponent;

    /**
     * @return float[]|int[]
     */
    public function getValue(): array
    {
        $this->initCurrentSortComponent();

        return [self::PARAM_NAME => $this->currentSortComponent->getValue()];
    }

    /**
     * @return float[]|int[]
     */
    public function getValueForCollapse(): array
    {
        $this->initCurrentSortComponent();

        return [self::PARAM_NAME => $this->currentSortComponent->getValueForCollapse()];
    }

    /**
     * Инициализация текущего компонента сортировки
     */
    public function initCurrentSortComponent()
    {
        $className = __NAMESPACE__ . '\\SortComponents\\' . ucfirst($this->filters->sort->getValues()->first() . 'Component');

        if (!class_exists($className)) {
            throw new \Exception('Missed class ' . $className);
        }

        $this->currentSortComponent = new $className($this->filters, $this->elasticWrapper);
    }
}
