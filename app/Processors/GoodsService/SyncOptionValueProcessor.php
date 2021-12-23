<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class SyncOptionValueProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected OptionValue $model;
    public static array $uniqueBy = ['id'];

    /**
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        $data = parent::prepareData();

        if (isset($data['show_value_in_short_set'])) {
            $data['show_value_in_short_set'] = $data['show_value_in_short_set'] === 'true' ? 1 : 0;
        }

        return $data;
    }
}
