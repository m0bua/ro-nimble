<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Продавец"
 *
 * @OA\Parameter (
 *     name="sellers",
 *     in="query",
 *     required=false,
 *     description="Список продавцов",
 *     example="sellers[]=rozetka&sellers[]=other",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Sellers extends AbstractFilter
{
    protected const PARAM = Filters::PARAM_SELLERS;

    /**
     * merchant_type = 1, если merchant_id равен 1,2,14,20,51,67,43,58,64,56
     * merchant_type = 2, если merchant_id не равен ˆ
     */

    /**
     * Sellers merchant types
     */
    public const MERCHANT_TYPE_ROZETKA = 1;
    public const MERCHANT_TYPE_OTHER = 2;

    protected static array $seller_params = [
        Filters::SELLER_ROZETKA => self::MERCHANT_TYPE_ROZETKA,
        Filters::SELLER_OTHER => self::MERCHANT_TYPE_OTHER,
    ];

    /**
     * @var string
     */
    protected string $name = Filters::SELLERS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Sellers constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Sellers
     */
    public static function fromRequest(FormRequest $request): Sellers
    {
        $sellers = $request->input(self::PARAM);

        if (empty($sellers)) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($sellers)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be array', self::PARAM)
            );
        }

        if (array_intersect_key(self::$seller_params, array_flip($sellers)) === []) {
            throw new BadRequestHttpException(sprintf(
                '\'%s\' parameter must be one of: %s',
                self::PARAM,
                implode(', ', array_keys(self::$seller_params))
            ));
        }

        return new static(array_values(array_intersect_key(self::$seller_params, array_flip($sellers))));
    }
}
