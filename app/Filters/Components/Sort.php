<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с параметром сортировки
 *
 * @OA\Parameter (
 *     name="sort",
 *     in="query",
 *     required=false,
 *     description="Сортировка",
 *     example="sort[]=cheap",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             enum={"cheap", "expensive", "popularity", "novelty", "action", "rank"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class Sort extends AbstractFilter
{
    protected const PARAM = Filters::PARAM_SORT;

    /**
     * Сортировка по умолчанию
     */
    public const DEFAULT_SORT = [Filters::SORT_RANK];

    /**
     * @var string
     */
    protected string $name = Filters::SORT;

    /**
     * @var array
     */
    protected array $values = self::DEFAULT_SORT;

    public static $availableParams = [
        Filters::SORT_RANK,
        Filters::SORT_CHEAP,
        Filters::SORT_EXPENSIVE,
        Filters::SORT_POPULARITY,
        Filters::SORT_NOVELTY,
        Filters::SORT_ACTION,
    ];

    /**
     * Sort constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Sort
     */
    public static function fromRequest(FormRequest $request): Sort
    {
        $requestSort = $request->input(self::PARAM);
        if (!\is_array($requestSort) || empty($requestSort[0])) {
            return new static(self::DEFAULT_SORT);
        }
        $sort = $requestSort[0];

        if (!in_array($sort, self::$availableParams) === []) {
            throw new BadRequestHttpException(sprintf(
                '\'%s\' parameter must be one of: %s',
                self::PARAM,
                implode(', ', self::$availableParams)
            ));
        }

        return new static([$sort]);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return current($this->values);
    }
}
