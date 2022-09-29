<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Models\Eloquent\PaymentParentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @OA\Parameter (
 *     name="payments",
 *     in="query",
 *     required=false,
 *     description="Список методов оплаты",
 *     example="payments[]=name1&payments[]=name2",
 *     @OA\Schema (
 *         type="array",
 *         @OA\Items (
 *             type="string"
 *         )
 *     )
 * ),
 */
class Payments extends AbstractFilter
{
    protected string $name = Filters::PAYMENTS;

    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    public static function fromRequest(FormRequest $request): AbstractFilter
    {
        $names = $request->input(Filters::PARAM_PAYMENTS, []);
        if (!is_array($names)) {
            throw new BadRequestHttpException(
                sprintf('"%s" parameter must be an array', Filters::PARAM_PAYMENTS)
            );
        }

        $ids = PaymentParentMethod::getIdsByNames($names)->toArray();

        return new static($ids);
    }
}
