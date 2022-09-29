<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Товары с акциями"
 *
 * @OA\Parameter (
 *     name="goods_with_promotions",
 *     in="query",
 *     required=false,
 *     description="Параметры фильтра Товары с акциями",
 *     example="goods_with_promotions[]=installments&goods_with_promotions[]=promotion",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             enum={"installments", "promotion"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class GoodsWithPromotions extends AbstractFilter
{
    public static $availableParams = [
        Filters::PROMOTION_GOODS_INSTALLMENT,
        Filters::PROMOTION_GOODS_PROMOTION,
    ];

    /**
     * @var string
     */
    protected string $name = Filters::PROMOTION_GOODS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * GoodsWithPromotions constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return GoodsWithPromotions
     */
    public static function fromRequest(FormRequest $request): GoodsWithPromotions
    {
        $params = $request->input(Filters::PARAM_PROMOTION_GOODS);

        if (!$params) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($params)) {
            throw new BadRequestHttpException(
                sprintf('"%s" parameter must be an array', Filters::PARAM_PROMOTION_GOODS)
            );
        }

        return new static(array_values(array_intersect($params, self::$availableParams)));
    }
}
