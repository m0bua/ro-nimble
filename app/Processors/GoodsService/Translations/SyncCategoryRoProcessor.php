<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class SyncCategoryRoProcessor extends SyncAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Category
     */
    protected Model $model;

    public static ?string $language = Language::RO;

    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
