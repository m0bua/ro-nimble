<?php
/**
 * Класс для работы с параметром сортировки
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class Sort extends AbstractFilter
{
    /**
     * Сортировка по умолчанию
     */
    public const DEFAULT_SORT = [Filters::SORT_RANK];

    /**
     * @var string
     */
    protected string $name = Filters::SORT;

    /**
     * @var array
     */
    protected array $values = self::DEFAULT_SORT;

    public static $availableParams = [
        Filters::SORT_RANK,
        Filters::SORT_CHEAP,
        Filters::SORT_EXPENSIVE,
        Filters::SORT_POPULARITY,
        Filters::SORT_NOVELTY,
        Filters::SORT_ACTION,
    ];

    /**
     * Sort constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Sort
     */
    public static function fromRequest(FormRequest $request): Sort
    {
        $sort = $request->input(Filters::PARAM_SORT, '');

        return new static(in_array($sort, self::$availableParams) ? [$sort] : self::DEFAULT_SORT);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return current($this->values);
    }
}
