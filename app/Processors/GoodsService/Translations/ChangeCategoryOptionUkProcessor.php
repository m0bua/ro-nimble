<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\CategoryOption;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class ChangeCategoryOptionUkProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var CategoryOption
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    public static ?array $compoundKey = [
        'category_id',
        'option_id',
    ];

    /**
     * ChangeCategoryOptionUkProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}