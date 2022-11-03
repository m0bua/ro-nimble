<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Статус товара"
 *
 * @OA\Parameter (
 *     name="sell_statuses",
 *     in="query",
 *     required=false,
 *     description="Статусы товаров",
 *     example="sell_statuses[]=limited&sell_statuses[]=available",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             enum={"waiting_for_supply", "limited", "available",
 *            "out_of_stock", "unavailable", "archive", "hidden"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class SellStatuses extends AbstractFilter
{
    protected const PARAM = Filters::PARAM_SELL_STATUSES;

    /**
     * @var string
     */
    protected string $name = Filters::SELL_STATUSES;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    public static $availableParams = [
        Filters::SELL_STATUS_WAITING_FOR_SUPPLY,
        Filters::SELL_STATUS_LIMITED,
        Filters::SELL_STATUS_AVAILABLE,
        Filters::SELL_STATUS_OUT_OF_STOCK,
        Filters::SELL_STATUS_UNAVAILABLE,
        Filters::SELL_STATUS_ARCHIVE,
        Filters::SELL_STATUS_HIDDEN,
    ];

    /**
     * SellStatuses constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return SellStatuses
     */
    public static function fromRequest(FormRequest $request): SellStatuses
    {
        $sellStatuses = $request->input(self::PARAM);

        if (empty($sellStatuses)) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($sellStatuses)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be array', self::PARAM)
            );
        }
        if (array_intersect($sellStatuses, self::$availableParams) === []) {
            throw new BadRequestHttpException(sprintf(
                '\'%s\' parameter must be one of: %s',
                self::PARAM,
                implode(', ', self::$availableParams)
            ));
        }

        return new static(array_intersect($sellStatuses, self::$availableParams));
    }
}
