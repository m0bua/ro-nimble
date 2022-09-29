<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Класс для работы с фильтром "Страница"
 *
 * @OA\Parameter (
 *     name="page",
 *     in="query",
 *     required=false,
 *     description="Страница",
 *     example="page[]=10-100500",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Page extends AbstractFilter
{
    public const PAGE_SEPARATOR = '-';
    public const DEFAULT_PAGE_MIN = 1;
    public const DEFAULT_PAGE_MAX = 1;
    public const PAGE_MIN_KEY = 'min';
    public const PAGE_MAX_KEY = 'max';

    /**
     * @var string
     */
    protected string $name = Filters::PAGE;

    /**
     * @var array
     */
    protected array $values = [
        self::PAGE_MIN_KEY => self::DEFAULT_PAGE_MIN,
        self::PAGE_MAX_KEY => self::DEFAULT_PAGE_MAX,
    ];

    /**
     * Page constructor.
     * @param int $min
     * @param int $max
     */
    public function __construct(int $min, int $max)
    {
        $this->values = [
            self::PAGE_MIN_KEY => $min,
            self::PAGE_MAX_KEY => $max,
        ];
    }

    /**
     * @param FilterRequest $request
     * @return Page
     */
    public static function fromRequest(FormRequest $request): Page
    {
        $requestPage = $request->input(Filters::PARAM_PAGE);
        if (!\is_array($requestPage) || empty($requestPage[0])) {
            return self::getResponse();
        }
        $page = $requestPage[0];

        if (empty($page) || $page === '0') {
            return self::getResponse();
        }

        if (Str::contains($page, self::PAGE_SEPARATOR)) {
            [$min, $max] = explode(self::PAGE_SEPARATOR, $page);
        } else {
            $min = $max = $page;
        }

        if (\in_array('', [$min, $max])) {
            return self::getResponse();
        }

        if ($max <= 0) {
            $max = self::DEFAULT_PAGE_MAX;
        }

        if ($min <= 0) {
            $min = self::DEFAULT_PAGE_MIN;
        }

        if ($min > $max) {
            $min = $max;
        }

        return self::getResponse($min, $max);
    }

    /**
     * @param int $min
     * @param int $max
     * @return Page
     */
    public static function getResponse($min = self::DEFAULT_PAGE_MIN, $max = self::DEFAULT_PAGE_MAX): Page
    {
        return new static($min, $max);
    }
}
