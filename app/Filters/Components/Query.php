<?php
/**
 * Класс для работы с параметром "query" для поиска
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class Query extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::QUERY;

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
    public static function fromRequest(FormRequest $request): Query
    {
        $query = (string) $request->input(Filters::PARAM_QUERY);

        return new static($query ? [mb_strtolower($query)] : Filters::DEFAULT_FILTER_VALUE);
    }
}
