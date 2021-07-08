<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use Illuminate\Database\Eloquent\Model;

class ChangeCategoryRoProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Category
     */
    protected Model $model;

    /**
     * ChangeCategoryRoProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
