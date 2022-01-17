<?php
/**
 * Класс для работы с фильтром "ID Акции"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        $promotion = abs((int) $request->input(Filters::PARAM_PROMOTION));

        return new static($promotion ? [$promotion] : Filters::DEFAULT_FILTER_VALUE);
    }
}
