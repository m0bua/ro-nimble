<?php

namespace App\ValueObjects;

use App\Interfaces\OptionsInterface;

class Options implements OptionsInterface
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
     * @return array
     */
    public function get(): array
    {
        return [
            'options' => $this->options,
            'option_names' => $this->optionNames,
            'option_values' => $this->optionValues,
            'option_values_names' => $this->optionValuesNames,
            'option_checked' => array_unique($this->optionChecked),
            'option_checked_names' => $this->optionCheckedNames,
            'option_sliders' => $this->optionSliders
        ];
    }

    /**
     * Валидация опций и наполнение свойств данными
     * @param $data
     */
    public function fill($data)
    {
        if (!$data || !is_array($data)) {
            return;
        }

        foreach ($data as $option) {
            if (!$option || !is_array($option)) {
                continue;
            }
            $details = $option['details'];
            if (is_null($details) || $details['state'] != self::STATUS_ACTIVE) {
                continue;
            }
            if (in_array($details['type'], self::OPTIONS_BY_TYPES['values'])) {
                if (!empty($option['values'])) {
                    $this->options[] = $details['id'];
                    $this->optionNames[] = (string)$details['name'];

                    foreach ($option['values'] as $value) {
                        if ($value['status'] != self::STATUS_ACTIVE) {
                            continue;
                        }

                        $this->optionValues[] = $value['id'];
                        $this->optionValuesNames[] = (string)$value['name'];
                    }
                }
            } elseif (in_array($details['type'], self::OPTIONS_BY_TYPES['integers'])) {
                $this->optionSliders[] = [
                    'id' => (int)$details['id'],
                    'name' => $details['name'],
                    'value' => (float)$option['value']
                ];
            } elseif (in_array($details['type'], self::OPTIONS_BY_TYPES['booleans'])) {
                $this->optionChecked[] = $details['id'];
                $this->optionCheckedNames[] = (string)$details['name'];
            }
        }
    }
}
