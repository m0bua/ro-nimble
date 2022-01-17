<?php
/**
 * Класс для работы с фильтром "Серия"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Eloquent\Series as SeriesModel;

class Series extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::SERIES;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Series constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Series
     */
    public static function fromRequest(FormRequest $request): Series
    {
        $series = $request->input(Filters::PARAM_SERIES);

        if (!$series) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        return new static(SeriesModel::getIdsByNames($series));
    }
}
