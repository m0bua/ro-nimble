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
    protected const PARAM = Filters::PARAM_CATEGORIES;

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
        $names = $request->input(self::PARAM);
        $error = sprintf('\'%s\' parameter must be array of strings', self::PARAM);

        if (empty($names)) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($names)) {
            throw new BadRequestHttpException($error);
        }

        foreach ($names as $name) {
            if (!is_string($name)) {
                throw new BadRequestHttpException($error);
            }
        }

        return new static(CategoryModel::getIdsByNames($names));
    }
}
