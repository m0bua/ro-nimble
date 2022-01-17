<?php

namespace App\Filters\Traits;

use App\Enums\Filters;

trait PrepareParamsTrait
{
    /**
     * Конвертация строки в массив по разделителю
     * @param null|string $value
     * @return array
     */
    public static function prepareArrayBySeparator($separator, ?string $value): array
    {
        if (empty($value)) {
            return Filters::DEFAULT_FILTER_VALUE;
        }

        return array_values(array_filter(array_map('trim', explode($separator, $value))));
    }

    /**
     * @param string $value
     * @return null|array
     */
    public static function prepareArrayByHyphen(?string $value): array
    {
        return self::prepareArrayBySeparator('-', $value);
    }
}
