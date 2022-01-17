<?php
/**
 * Класс для работы с фильтрами - чекбоксами
 */
namespace App\Filters\Components\Options;

use App\Enums\Filters;

class OptionChecked extends AbstractOptionFilter
{
    /**
     * Значение фильтра CheckBox
     * @var string
     */
    public const FILTER_CHECKBOX_VALUE = '1';

    /**
     * @var string
     */
    protected string $name = Filters::OPTION_CHECKED;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * OptionChecked constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param array $params
     * @return OptionChecked
     */
    public static function fromRequest(array $params): OptionChecked
    {
        foreach ($params as $optionId => $value) {
            if ($value != self::FILTER_CHECKBOX_VALUE) {
                unset($params[$optionId]);
            }
        }

        return new static($params ? array_values(array_flip($params)) : Filters::DEFAULT_FILTER_VALUE);
    }
}
