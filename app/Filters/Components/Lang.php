<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Язык"
 *
 * @OA\Parameter (
 *     name="lang",
 *     in="query",
 *     required=false,
 *     description="Параметр языка",
 *     example="lang[]=ua",
 *     @OA\Schema (
 *         type="array",
 *         default="[ru]",
 *         @OA\Items (
 *             enum={"ua","ru","uz"},
 *             type="string"
 *         )
 *     )
 * )
 */
class Lang extends AbstractFilter
{
    public static $availableParams = [];

    protected const PARAM = Filters::PARAM_LANG;

    /**
     * @var string
     */
    protected string $name = Filters::LANG;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Lang constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Lang
     */
    public static function fromRequest(FormRequest $request): Lang
    {
        // todo: activate param

        // $params = $request->input(self::PARAM);

        // if (empty($params)) {
        //     return new static(Filters::DEFAULT_FILTER_VALUE);
        // }

        // if (!is_array($params)) {
        //     throw new BadRequestHttpException(
        //         sprintf('\'%s\' parameter must be array', self::PARAM)
        //     );
        // }

        // if (array_intersect($params, self::$availableParams) === []) {
        //     throw new BadRequestHttpException(sprintf(
        //         '\'%s\' parameter must be one of: %s',
        //         self::PARAM,
        //         implode(', ', self::$availableParams)
        //     ));
        // }

        return new static(isset($params) ? $params : Filters::DEFAULT_FILTER_VALUE);
    }
}
