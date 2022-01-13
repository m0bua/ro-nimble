<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\CategoryOption;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class SyncCategoryOptionUkProcessor extends SyncAbstractTranslationProcessor
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
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
