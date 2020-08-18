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
     * @var string
     */
    private $options = [];

    /**
     * @var string
     */
    private $optionNames = [];

    /**
     * @var string
     */
    private $optionValues = [];

    /**
     * @var string
     */
    private $optionValuesNames = [];

    /**
     * @var string
     */
    private $optionsChecked = [];

    /**
     * @var string
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
                $this->options[] = $details['id'];
                $this->optionNames[] = (string)$details['name'];

                foreach ($option['values'] as $value) {
                    if ($value['status'] != self::STATUS_ACTIVE) {
                        continue;
                    }

                    $this->optionValues[] = $value['id'];
                    $this->optionValuesNames[] = (string)$value['name'];
                }
            } elseif (in_array($details['type'], self::OPTIONS_BY_TYPES['integers'])) {
                $this->optionSliders[$details['id']] = $option['value'];
            } elseif (in_array($details['type'], self::OPTIONS_BY_TYPES['booleans'])) {
                $this->optionsChecked[] = $details['id'];
            }
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'options' => $this->options,
            'option_names' => $this->optionNames,
            'option_values' => $this->optionValues,
            'option_values_names' => $this->optionValuesNames,
            'options_checked' => array_unique($this->optionsChecked),
            'option_sliders' => $this->optionSliders
        ];
    }
}
