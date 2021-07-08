<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class ChangeOptionValueRoProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var OptionValue
     */
    protected Model $model;

    public static ?string $language = Language::RO;

    /**
     * ChangeOptionValueRoProcessor constructor.
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
