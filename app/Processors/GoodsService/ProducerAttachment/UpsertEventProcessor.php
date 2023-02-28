<?php

namespace App\Processors\GoodsService\ProducerAttachment;

use App\Processors\UpsertProcessor;
use App\Models\Eloquent\ProducersAttachment;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'id',
    ];

    protected string $dataRoot = 'data';

    /**
     * @param ProducersAttachment $model
     */
    public function __construct(ProducersAttachment $model)
    {
        $this->model = $model;
    }

    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        if ($data['group_name'] === 'images' && $data['variant'] === 'original') {
            $this->model->upsert($data, $uniqueBy, $update);
        }

        $this->saveTranslations();

        return true;
    }
}
