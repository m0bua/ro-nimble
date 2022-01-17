<?php
/**
 * Класс для работы с фильтром "Цена"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Filters\Traits\PrepareParamsTrait;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class Price extends AbstractFilter
{
    use PrepareParamsTrait;

    public const SEPARATOR = '-';
    public const MIN_KEY = 'min';
    public const MAX_KEY = 'max';

    /**
     * @var string
     */
    protected string $name = Filters::PRICE;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Price constructor.
     * @param int $min
     * @param int $max
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Price
     */
    public static function fromRequest(FormRequest $request): Price
    {
        $price = $request->input(Filters::PARAM_PRICE);

        if (!Str::contains($price, self::SEPARATOR)) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        $price = self::prepareArrayByHyphen($request->input(Filters::PARAM_PRICE));

        if (count($price) != 2
            || ((int) $price[0] == 0 && (int) $price[1] == 0)
            || ((int) $price[0] > (int) $price[1]))
        {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        return new static([
            self::MIN_KEY => $price[0],
            self::MAX_KEY => $price[1]
        ]);
    }
}
