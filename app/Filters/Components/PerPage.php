<?php

namespace App\Filters\Components;

use App\Enums\Config;
use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Количество на странице"
 *
 * @OA\Parameter (
 *     name="per_page",
 *     in="query",
 *     required=false,
 *     description="Элементов на странице",
 *     example="per_page[]=30",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="integer"
 *         )
 *     )
 * )
 */
class PerPage extends AbstractFilter
{
    /**
     * Дефолтное количество товаров на странице
     */
    public const DEFAULT_VALUE = [Config::CATALOG_GOODS_LIMIT];

    protected const PARAM = Filters::PARAM_PER_PAGE;

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
        $requestPerPage = $request->input(self::PARAM);
        if (!\is_array($requestPerPage) || empty($requestPerPage[0])) {
            return new static(self::DEFAULT_VALUE);
        }
        $perPage = $requestPerPage[0];

        if (!is_numeric($perPage)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be positive integer', self::PARAM)
            );
        }

        $perPage = (int)$perPage;

        if ($perPage <= 0 || $perPage > self::DEFAULT_VALUE[0]) {
            throw new BadRequestHttpException(sprintf(
                '\'%s\' parameter must be between 0 and %s',
                self::PARAM,
                self::DEFAULT_VALUE[0]
            ));
        }

        return new static([$perPage]);
    }
}
