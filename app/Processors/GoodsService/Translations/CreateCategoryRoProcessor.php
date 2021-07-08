<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use Illuminate\Database\Eloquent\Model;

class CreateCategoryRoProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Category
     */
    protected Model $model;

    /**
     * CreateCategoryRoProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
