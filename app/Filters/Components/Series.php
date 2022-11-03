<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Eloquent\Series as SeriesModel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Серия"
 *
 * @OA\Parameter (
 *     name="series",
 *     in="query",
 *     required=false,
 *     description="Выбор серии производителя",
 *     example="series[]=asuspro",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Series extends AbstractFilter
{
    protected const PARAM = Filters::PARAM_SERIES;

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
        $series = $request->input(self::PARAM);

        if (empty($series)) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($series)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be array', self::PARAM)
            );
        }

        return new static(SeriesModel::getIdsByNames($series));
    }
}
