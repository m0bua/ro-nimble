<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Filters\Traits\PrepareParamsTrait;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Цена"
 *
 * @OA\Parameter (
 *     name="price",
 *     in="query",
 *     required=false,
 *     description="Диапазон цены",
 *     example="price[]=100-200",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Price extends AbstractFilter
{
    use PrepareParamsTrait;

    protected const PARAM = Filters::PARAM_PRICE;

    public const SEPARATOR = '-';

    public const MIN_KEY = 'min';

    public const MAX_KEY = 'max';

    /**
     * @var string
     */
    protected string $name = Filters::PRICE;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Price constructor.
     * @param int $min
     * @param int $max
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Price
     */
    public static function fromRequest(FormRequest $request): Price
    {
        $requestPrice = $request->input(self::PARAM);

        if (!\is_array($requestPrice) || empty($requestPrice[0])) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        $price = self::prepareArrayByHyphen($requestPrice[0]);

        if (!is_numeric($price[0])) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be numeric', self::PARAM)
            );
        }

        if (empty($price[1])) {
            return new static([
                self::MIN_KEY => $price[0],
                self::MAX_KEY => $price[0]
            ]);
        }

        if (
            count($price) > 2 || !is_numeric($price[1])
            || ((int)$price[0] == 0 && (int)$price[1] == 0)
            || ((int)$price[0] > (int)$price[1])
        ) {
            throw new BadRequestHttpException(sprintf(
                '\'%s\' parameter must contain two numbers'
                    . ' separated by single \'%s\' from lower to higher',
                self::PARAM,
                self::SEPARATOR
            ));
        }

        return new static([
            self::MIN_KEY => $price[0],
            self::MAX_KEY => $price[1]
        ]);
    }
}
