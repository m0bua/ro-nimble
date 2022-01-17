<?php
/**
 * Класс для работы с фильтром "Программа лояльности" (опция "С бонусами")
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class Bonus extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::BONUS;

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
    public static function fromRequest(FormRequest $request): Bonus
    {
        $bonus = $request->input(Filters::PARAM_BONUS);

        return new static($bonus == Filters::PARAM_BONUS ? [$bonus] : Filters::DEFAULT_FILTER_VALUE);
    }
}
