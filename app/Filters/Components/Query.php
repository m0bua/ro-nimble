<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для работы с параметром "query" для поиска
 *
 * @OA\Parameter (
 *     name="query",
 *     in="query",
 *     required=false,
 *     description="query",
 *     example="query[]=query",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Query extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::QUERY;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Bonus constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Bonus
     */
    public static function fromRequest(FormRequest $request): Query
    {
        $requestQuery = $request->input(Filters::PARAM_QUERY);
        if (!\is_array($requestQuery) || empty($requestQuery[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $query = (string) $requestQuery[0];

        return new static($query ? [mb_strtolower($query)] : Filters::DEFAULT_FILTER_VALUE);
    }
}
