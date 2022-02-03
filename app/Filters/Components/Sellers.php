<?php
/**
 * Класс для работы с фильтром "Продавец"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Sellers extends AbstractFilter
{
    /**
     * merchant_type = 1, если merchant_id равен 1 или 2
     * merchant_type = 2, если merchant_id равен 43
     * merchant_type = 3, если merchant_id не равен 1,2 или 43
     */

    /**
     * Sellers merchant types
     */
    public const MERCHANT_TYPE_ROZETKA = 1;
    public const MERCHANT_TYPE_FULFILLMENT = 2;
    public const MERCHANT_TYPE_OTHER = 3;

    protected static array $seller_params = [
        Filters::SELLER_ROZETKA => self::MERCHANT_TYPE_ROZETKA,
        Filters::SELLER_FULFILLMENT => self::MERCHANT_TYPE_FULFILLMENT,
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
        $sellers = $request->input(Filters::PARAM_SELLERS);

        if (!$sellers) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($sellers)) {
            throw new BadRequestHttpException(
                sprintf('"%s" parameter must be an array'), Filters::PARAM_SELLERS
            );
        }

        return new static(array_values(array_intersect_key(self::$seller_params, array_flip($sellers))));
    }
}
