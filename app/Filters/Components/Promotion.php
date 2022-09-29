<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для работы с фильтром "ID Акции"
 *
 * @OA\Parameter (
 *     name="promotion_id",
 *     in="query",
 *     required=false,
 *     description="Текущая акция",
 *     example="promotion_id[]=377185",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="integer"
 *         )
 *     )
 *     ),
 */
class Promotion extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::PROMOTION;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Promotion constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Promotion
     */
    public static function fromRequest(FormRequest $request): Promotion
    {
        $requestPromotion = $request->input(Filters::PARAM_PROMOTION);
        if (!\is_array($requestPromotion) || empty($requestPromotion[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $promotion = abs((int) $requestPromotion[0]);

        return new static($promotion ? [$promotion] : Filters::DEFAULT_FILTER_VALUE);
    }
}
