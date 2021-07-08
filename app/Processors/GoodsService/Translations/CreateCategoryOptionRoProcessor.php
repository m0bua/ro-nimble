<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\CategoryOption;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class CreateCategoryOptionRoProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var CategoryOption
     */
    protected Model $model;

    public static ?string $language = Language::RO;

    public static ?array $compoundKey = [
        'category_id',
        'option_id',
    ];

    /**
     * CreateCategoryOptionRoProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
