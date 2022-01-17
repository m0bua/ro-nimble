<?php
/**
 * Класс для работы с параметром для вывода сгруппированных товаров
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        $singleGoods = (int) filter_var($request->input(Filters::PARAM_SINGLE_GOODS), FILTER_VALIDATE_BOOLEAN);

        return new static($singleGoods ? [$singleGoods] : Filters::DEFAULT_FILTER_VALUE);
    }

    /**
     * @return bool
     */
    public function isCheck(): bool
    {
        return !!$this->values;
    }
}
