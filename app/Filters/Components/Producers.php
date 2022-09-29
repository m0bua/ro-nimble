<?php
/**
 * Класс для работы с фильтром "Производитель"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Eloquent\Producer as ProducerModel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @OA\Parameter (
 *     name="producers",
 *     in="query",
 *     required=false,
 *     description="Список производителей ('name' или 'v{id}')",
 *     example="producer[]=v1234&producer[]=toshiba",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
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
        $producersNames = $request->input(Filters::PARAM_PRODUCER);

        if (!$producersNames) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($producersNames)) {
            throw new BadRequestHttpException(
                sprintf('"%s" parameter must be an array', Filters::PARAM_PRODUCER)
            );
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
