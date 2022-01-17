<?php
/**
 * Класс для работы с фильтром "Язык"
 */
namespace App\Filters\Components;

use App;
use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class Lang extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::LANG;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Lang constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Lang
     */
    public static function fromRequest(FormRequest $request): Lang
    {
        return new static(Filters::DEFAULT_FILTER_VALUE);
    }
}
