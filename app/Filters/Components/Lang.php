<?php

namespace App\Filters\Components;

use App;
use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для работы с фильтром "Язык"
 *
 * @OA\Parameter (
 *     name="lang",
 *     in="query",
 *     required=false,
 *     description="Параметр языка",
 *     example="lang[]=ua",
 *     @OA\Schema (
 *         type="array",
 *         default="[ru]",
 *         @OA\Items (
 *             enum={"ua","ru","uz"},
 *             type="string"
 *         )
 *     )
 * )
 */
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
