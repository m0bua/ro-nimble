<?php

namespace App\ValueObjects;

use App\Helpers\ConvertString;
use Exception;

class Options
{
    /**
     * Option types
     */
    public const OPTIONS_BY_TYPES = [
        'integers' => [
            'Integer',
            'Decimal'
        ],
        'values' => [
            'ComboBox',
            'CheckBoxGroup',
            'List',
            'ListValues',
            'CheckBoxGroupValues'
        ],
        'booleans' => [
            'CheckBox'
        ],
        'text' => [
            'MultiText',
            'RichText',
            'RichTextVideo',
            'Suggest',
            'Text',
            'TextArea',
            'TextInput',
            'ColorPicker'
        ],
    ];

    public const STATUS_ACTIVE = 'active';

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $optionNames = [];

    /**
     * @var array
     */
    private $optionValues = [];

    /**
     * @var array
     */
    private $optionValuesNames = [];

    /**
     * @var array
     */
    private $optionChecked = [];

    /**
     * @var array
     */
    private $optionCheckedNames = [];

    /**
     * @var array
     */
    private $optionSliders = [];

    /**
     * Options constructor.
     * @param $data
     */
    public function __construct($data)
    {
        try {
            $this->fillOptions($data);
        } catch (\Throwable $t) {
            report($t);
        }
    }

    /**
     * Валидация опций и наполнение свойств данными
     * @param $data
     */
    private function fillOptions($data)
    {
        if (!$data || !is_array($data)) {
            return;
        }

        foreach($data as $option) {
            if (!$option) {
                continue;
            }

            $details = $option['details'];

            if ($details['state'] != self::STATUS_ACTIVE) {
                continue;
            }

            if (in_array($details['type'], self::OPTIONS_BY_TYPES['values'])) {
                $this->options[$details['id']] = null;
                $this->optionNames[$details['name']] = null;

                foreach ($details['values'] as $value) {
                    if ($value['status'] != self::STATUS_ACTIVE) {
                        continue;
                    }

                    $this->optionValues[$value['id']] = null;
                    $this->optionValuesNames[$value['name']] = null;
                }
            } elseif (in_array($details['type'], self::OPTIONS_BY_TYPES['integers'])) {
                $this->optionSliders[$details['name']] = $option['value'];
            } elseif (in_array($details['type'], self::OPTIONS_BY_TYPES['booleans'])) {
                $this->optionChecked[$details['id']] = null;
                $this->optionCheckedNames[$details['name']] = null;
            }
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'options' => array_keys($this->options),
            'option_names' => array_map('strval', array_keys($this->optionNames)),
            'option_values' => array_keys($this->optionValues),
            'option_values_names' => array_map('strval', array_keys($this->optionValuesNames)),
            'option_checked' => array_keys($this->optionChecked),
            'option_checked_names' => array_map('strval', array_keys($this->optionCheckedNames)),
            'option_sliders' => $this->optionSliders
        ];
    }
}
