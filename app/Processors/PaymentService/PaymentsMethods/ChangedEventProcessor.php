<?php

namespace App\Processors\PaymentService\PaymentsMethods;

use App\Models\Eloquent\PaymentMethod;
use App\Processors\UpsertProcessor;
use App\Support\Language;

class ChangedEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'id',
    ];

    protected string $dataRoot = 'fields_data';

    /**
     * ChangedEventProcessor constructor.
     * @param PaymentMethod $model
     */
    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    protected function afterProcess(): void
    {
        /** @var PaymentMethod $entity */
        $entity = $this->resolveEntity();
        $entity->setTranslation(Language::UK, 'title', $this->data['title_ua']);
    }
}
