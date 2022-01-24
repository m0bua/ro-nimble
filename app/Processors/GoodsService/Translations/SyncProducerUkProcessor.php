<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class SyncProducerUkProcessor extends SyncAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Producer
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}