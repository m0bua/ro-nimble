<?php

namespace App\Processors\MarketingService\Labels\Label;

use App\Models\Eloquent\Label;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';

    protected array $compoundKey = [
        'id',
        'country_code',
    ];

    /**
     * @param Label $model
     */
    public function __construct(Label $model)
    {
        $this->model = $model;
    }
}
