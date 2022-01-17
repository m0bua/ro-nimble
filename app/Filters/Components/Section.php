<?php
/**
 * Класс для работы с секциями фильтра "Все товары"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class Section extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::SECTION;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Section constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Section
     */
    public static function fromRequest(FormRequest $request): Section
    {
        $section = abs((int) $request->input(Filters::PARAM_SECTION));

        return new static($section ? [$section] : Filters::DEFAULT_FILTER_VALUE);
    }
}
