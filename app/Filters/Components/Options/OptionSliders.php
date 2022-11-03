<?php
/**
 * Класс для работы с фильтрами - слайдерами
 */
namespace App\Filters\Components\Options;

use App\Enums\Filters;

class OptionSliders extends AbstractOptionFilter
{
    /**
     * Шаблон для слайдеров
     */
    public const SLIDER_REGEX = '/^(-?\d+(?:\.{1}\d+)?)-(-?\d+(?:\.{1}\d+)?)$/';

    /**
     * @var string
     */
    public const MIN_KEY = 'min';

    /**
     * @var string
     */
    public const MAX_KEY = 'max';

    /**
     * @var int
     */
    public const PRECISION = 3;

    /**
     * @var string
     */
    protected string $name = Filters::OPTION_SLIDERS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * OptionSliders constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param array $params
     * @return OptionSliders
     */
    public static function fromRequest(array $params): OptionSliders
    {
        $result = Filters::DEFAULT_FILTER_VALUE;

        foreach ($params as $optionId => $value) {
            if (preg_match(self::SLIDER_REGEX, trim($value[0]), $matches)) {
                $value = array_map('floatval', [$matches[1], $matches[2]]);

                if (
                    count($value) != 2
                    || ((float)$value[0] == 0 && (float)$value[1] == 0)
                ) {
                    continue;
                }

                $result[$optionId] = [
                    self::MIN_KEY => round($value[0], self::PRECISION),
                    self::MAX_KEY => round($value[1], self::PRECISION)
                ];
            }
        }

        return new static($result);
    }
}
