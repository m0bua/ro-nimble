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
    protected const PARAM = Filters::PARAM_PAYMENTS;

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
        $names = $request->input(self::PARAM, Filters::DEFAULT_FILTER_VALUE);
        $error = sprintf('\'%s\' parameter must be array of strings', self::PARAM);

        if (empty($names)) {
            return new static(Filters::DEFAULT_FILTER_VALUE);
        }

        if (!is_array($names)) {
            throw new BadRequestHttpException($error);
        }

        foreach ($names as $name) {
            if (!is_string($name)) {
                throw new BadRequestHttpException($error);
            }
        }

        return new static(PaymentParentMethod::getIdsByNames($names)->toArray());
    }
}
