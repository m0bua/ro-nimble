<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    protected const PARAM = Filters::PARAM_PROMOTION;

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
        $requestPromotion = $request->input(self::PARAM);
        if (!\is_array($requestPromotion) || empty($requestPromotion[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $promotion = $requestPromotion[0];
        $error = sprintf('\'%s\' parameter must be positive integer', self::PARAM);

        if (!is_numeric($promotion)) {
            throw new BadRequestHttpException($error);
        }

        $promotion = (int)$promotion;

        if ($promotion < 1) {
            throw new BadRequestHttpException($error);
        }

        return new static($promotion ? [$promotion] : Filters::DEFAULT_FILTER_VALUE);
    }
}
