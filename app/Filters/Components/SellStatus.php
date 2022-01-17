<?php
/**
 * Класс для работы с фильтром "Статус товара"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class SellStatus extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::SELL_STATUS;

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
     * SellStatus constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return SellStatus
     */
    public static function fromRequest(FormRequest $request): SellStatus
    {
        $sellStatuses = $request->input(Filters::PARAM_SELL_STATUS);

        if (!$sellStatuses) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        return new static(array_intersect($sellStatuses, self::$availableParams));
    }
}
