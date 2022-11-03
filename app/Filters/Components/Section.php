<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    protected const PARAM = Filters::PARAM_SECTION;

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
        $requestSection = $request->input(self::PARAM);
        if (!\is_array($requestSection) || empty($requestSection[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $section = $requestSection[0];
        $error = sprintf('\'%s\' parameter must be positive integer', self::PARAM);

        if (!is_numeric($section)) {
            throw new BadRequestHttpException($error);
        }

        $section = (int)$section;

        if ($section < 1) {
            throw new BadRequestHttpException($error);
        }

        return new static([$section]);
    }
}
