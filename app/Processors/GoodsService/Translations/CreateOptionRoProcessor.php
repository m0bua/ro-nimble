<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Option;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class CreateOptionRoProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Option
     */
    protected Model $model;

    public static ?string $language = Language::RO;

    /**
     * CreateOptionRoProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
