<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class ChangeProducerUkProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Producer
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * ChangeProducerUkProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
