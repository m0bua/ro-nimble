<?php
/**
 * Класс для работы с фильтром "Новый - б/у"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Filters\Traits\PrepareParamsTrait;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

class State extends AbstractFilter
{
    public static $availableParams = [
        Filters::STATE_NEW,
        Filters::STATE_USED,
        Filters::STATE_REFURBISHED,
    ];

    /**
     * @var string
     */
    protected string $name = Filters::STATE;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * State constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return State
     */
    public static function fromRequest(FormRequest $request): State
    {
        $states = $request->input(Filters::PARAM_STATE);

        if (!$states) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        return new static(array_intersect($states, self::$availableParams));
    }
}
