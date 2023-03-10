<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Программа лояльности" (опция "С бонусами")
 *
 * @OA\Parameter (
 *     name="with_bonus",
 *     in="query",
 *     required=false,
 *     description="Товары с бонусами",
 *     example="with_bonus[]=with_bonus",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             enum={"with_bonus"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class Bonus extends AbstractFilter
{
    protected const PARAM = Filters::PARAM_BONUS;

    /**
     * @var string
     */
    protected string $name = Filters::BONUS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Bonus constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Bonus
     */
    public static function fromRequest(FormRequest $request): Bonus
    {
        $requestBonus = $request->input(self::PARAM);
        if (!\is_array($requestBonus) || empty($requestBonus[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }
        $bonus = $requestBonus[0];

        if (!is_string($bonus)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be string', self::PARAM)
            );
        }

        return new static($bonus == self::PARAM ? [$bonus] : Filters::DEFAULT_FILTER_VALUE);
    }
}
