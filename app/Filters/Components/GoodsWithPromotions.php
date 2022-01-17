<?php
/**
 * Класс для работы с фильтром "Товары с акциями"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        $states = $request->input(Filters::PARAM_PROMOTION_GOODS);

        if (!$states) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        return new static(array_values(array_intersect($states, self::$availableParams)));
    }
}
