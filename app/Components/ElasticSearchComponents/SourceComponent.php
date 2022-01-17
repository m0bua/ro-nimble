<?php
/**
 * Класс для генерации параметра "_source" (Список полей для вывода)
 * Class SourceComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

class SourceComponent extends BaseComponent
{
    public const PARAM_NAME = '_source';

    private array $fields;

    /**
     * @param array $fields
     * @return SourceComponent
     */
    public function setFields(array $fields): SourceComponent
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return array[]
     */
    public function getValue(): array
    {
        return [self::PARAM_NAME => $this->fields];
    }
}
