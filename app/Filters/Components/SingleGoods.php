<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для работы с параметром для вывода сгруппированных товаров
 *
 * @OA\Parameter (
 *     name="single_goods",
 *     in="query",
 *     required=false,
 *     description="Группировка товаров",
 *     example="single_goods[]=1",
 *     @OA\Schema (
 *         type="array",
 *         default="[0]",
 *         @OA\Items (
 *             enum={"0", "1"},
 *             type="integer"
 *         )
 *     )
 * ),
 */
class SingleGoods extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::SINGLE_GOODS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * SingleGoods constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return SingleGoods
     */
    public static function fromRequest(FormRequest $request): SingleGoods
    {
        $requestSingleGoods = $request->input(Filters::PARAM_SINGLE_GOODS);
        if (!\is_array($requestSingleGoods) || empty($requestSingleGoods[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $requestSingleGoods = (int) filter_var($requestSingleGoods[0], FILTER_VALIDATE_BOOLEAN);

        return new static($requestSingleGoods ? [$requestSingleGoods] : Filters::DEFAULT_FILTER_VALUE);
    }

    /**
     * @return bool
     */
    public function isCheck(): bool
    {
        return !!$this->values;
    }
}
