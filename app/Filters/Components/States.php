<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Filters\Traits\PrepareParamsTrait;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Новый - б/у"
 *
 * @OA\Parameter (
 *     name="states",
 *     in="query",
 *     required=false,
 *     description="Выбор товаром Новый - Б/у",
 *     example="states[]=new&states[]=used&states[]=refurbished",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             enum={"new","used","refurbished"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class States extends AbstractFilter
{
    public static $availableParams = [
        Filters::STATE_NEW,
        Filters::STATE_USED,
        Filters::STATE_REFURBISHED,
    ];

    /**
     * @var string
     */
    protected string $name = Filters::STATES;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * States constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return States
     */
    public static function fromRequest(FormRequest $request): States
    {
        $states = $request->input(Filters::PARAM_STATES);

        if (!$states) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($states)) {
            throw new BadRequestHttpException(
                sprintf('"%s" parameter must be an array', Filters::PARAM_STATES)
            );
        }

        return new static(array_intersect($states, self::$availableParams));
    }
}
