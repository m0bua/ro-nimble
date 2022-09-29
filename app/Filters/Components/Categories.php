<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Eloquent\Category as CategoryModel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Категория"
 *
 * @OA\Parameter (
 *     name="categories",
 *     in="query",
 *     required=false,
 *     description="Список категорий",
 *     example="categories[]=name1&categories[]=name2",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Categories extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::CATEGORIES;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Categories constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Categories
     */
    public static function fromRequest(FormRequest $request): Categories
    {
        $categoriesNames = $request->input(Filters::PARAM_CATEGORIES);

        if (!$categoriesNames) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($categoriesNames)) {
            throw new BadRequestHttpException(
                sprintf('"%s" parameter must be an array', Filters::PARAM_CATEGORIES)
            );
        }

        return new static(CategoryModel::getIdsByNames($categoriesNames));
    }
}
