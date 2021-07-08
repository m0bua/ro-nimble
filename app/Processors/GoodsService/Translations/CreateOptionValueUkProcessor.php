<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class CreateOptionValueUkProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var OptionValue
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * CreateOptionValueUkProcessor constructor.
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
