<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    protected const PARAM = Filters::PARAM_QUERY;

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
        $requestQuery = $request->input(self::PARAM);
        if (!\is_array($requestQuery) || empty($requestQuery[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $query = $requestQuery[0];

        if (!is_string($query)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be string', self::PARAM)
            );
        }

        return new static([mb_strtolower($query)]);
    }
}
