<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для работы с секциями фильтра "Все товары"
 *
 * @OA\Parameter (
 *     name="section_id",
 *     in="query",
 *     required=false,
 *     description="Текущая секция(категория)",
 *     example="section_id[]=123",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="integer"
 *         )
 *     )
 * ),
 */
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
        $requestSection = $request->input(Filters::PARAM_SECTION);
        if (!\is_array($requestSection) || empty($requestSection[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $section = abs((int) $requestSection[0]);

        return new static($section ? [$section] : Filters::DEFAULT_FILTER_VALUE);
    }
}
