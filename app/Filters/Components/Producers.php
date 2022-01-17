<?php
/**
 * Класс для работы с фильтром "Производитель"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Eloquent\Producer as ProducerModel;

class Producers extends AbstractFilter
{
    /**
     * @var string
     */
    protected string $name = Filters::PRODUCERS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Producers constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Producers
     */
    public static function fromRequest(FormRequest $request): Producers
    {
        $producersNames = $request->input(Filters::PARAM_PRODUCERS);

        if (!$producersNames) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        //если значение продюсера 'v1234', проверяем по id, активен ли он
        $needCheckIds = [];
        foreach ($producersNames as $key => $producerName) {
            if (preg_match('/^v(\d+)$/', $producerName, $matches)) {
                $needCheckIds[] = $matches[1];
                unset($producersNames[$key]);
            }
        }

        $producersIds = ProducerModel::getIdsByNames($producersNames);

        if ($needCheckIds) {
            $producersIds = array_merge($producersIds, ProducerModel::getActiveByIds($needCheckIds));
        }

        return new static($producersIds);
    }

    /**
     * Указывает нужно ли отображать фильтр "Серия"
     * @return bool
     */
    public function isViewSeries()
    {
        return count($this->getValues()) == 1;
    }
}
