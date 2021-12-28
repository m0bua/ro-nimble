<?php
/**
 * Class Elastic
 * @package App\Enums
 */

namespace App\Enums;

class Elastic
{
    /**
     * Параметры/поля для построения запросов в elasticsearch
     */
    public const FIELD_OPTION_VALUES = 'option_values';
    public const FIELD_OPTION_SLIDERS = 'option_sliders';
    public const FIELD_OPTION_SLIDERS_ID = 'option_sliders.id';
    public const FIELD_OPTION_SLIDERS_VALUE = 'option_sliders.value';
    public const FIELD_OPTION_CHECKED = 'option_checked';
}
