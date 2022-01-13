<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class SyncOptionValueUkProcessor extends SyncAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var OptionValue
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
