<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeOptionValueProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected OptionValue $model;

    /**
     * ChangeOptionValueProcessor constructor.
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
