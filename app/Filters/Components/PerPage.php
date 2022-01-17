<?php
/**
 * Класс для работы с фильтром "Количество на странице"
 */
namespace App\Filters\Components;

use App\Enums\Config;
use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class PerPage extends AbstractFilter
{
    /**
     * Дефолтное количество товаров на странице
     */
    public const DEFAULT_VALUE = [Config::CATALOG_GOODS_LIMIT];

    /**
     * @var string
     */
    protected string $name = Filters::PER_PAGE;

    /**
     * @var array
     */
    protected array $values = self::DEFAULT_VALUE;

    /**
     * PerPage constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return PerPage
     */
    public static function fromRequest(FormRequest $request): PerPage
    {
        $perPage = (int) $request->input(Filters::PARAM_PER_PAGE);

        return new static($perPage <= 0 || $perPage > self::DEFAULT_VALUE[0] ? self::DEFAULT_VALUE : [$perPage]);
    }
}
