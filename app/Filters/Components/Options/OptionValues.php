<?php
/**
 * Класс для работы с динамичными фильтрами
 */
namespace App\Filters\Components\Options;

use App\Enums\Filters;
use App\Filters\Traits\PrepareParamsTrait;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionValue;

class OptionValues extends AbstractOptionFilter
{
    use PrepareParamsTrait;

    public const SUPPLEMENTING = 'supplementing';
    public const KEY_ADDITION = 'addition';
    public const KEY_VALUES = 'values';
    /**
     * @var string
     */
    protected string $name = Filters::OPTION_VALUES;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * OptionValues constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param array $params
     * @return OptionValues
     */
    public static function fromRequest(array $params): OptionValues
    {
        $result = Filters::DEFAULT_FILTER_VALUE;

        foreach ($params as $param) {
            /** @var Option $option */
            $option = $param['option'];
            $optionValues = $param['optionValues'];

            if (!$optionValues) {
                continue;
            }

            $optionValues = OptionValue::getOptValueIdsByNames($option->id, $optionValues);

            if ($optionValues) {
                $result[$option->id] = [
                    self::KEY_ADDITION => self::isAddition($option),
                    self::KEY_VALUES => $optionValues
                ];
            }
        }

        return new static($result);
    }

    /**
     * Создаем условия для исключающих фильтров
     * @param Option $option
     * @return bool
     */
    public static function isAddition(Option $option): bool {
        return !$option->filtering_type || $option->filtering_type == self::SUPPLEMENTING;
    }
}
