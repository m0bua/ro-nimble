<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс для работы с фильтром "Код страны"
 *
 * @OA\Parameter (
 *     name="country",
 *     in="query",
 *     required=false,
 *     description="Параметр страны",
 *     example="country[]=ua",
 *     @OA\Schema (
 *         type="array",
 *         default="[ua]",
 *         @OA\Items (
 *             enum={"ua","uz","pl"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class Country extends AbstractFilter
{
    public const DEFAULT_VALUE = [Filters::COUNTRY_UA];

    protected const PARAM = Filters::PARAM_COUNTRY;

    /**
     * @var string
     */
    protected string $name = Filters::COUNTRY;

    /**
     * @var array
     */
    protected array $values = self::DEFAULT_VALUE;

    public static $availableParams = [
        Filters::COUNTRY_UA,
        Filters::COUNTRY_UZ,
        Filters::COUNTRY_PL,
    ];

    /**
     * CountryCode constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param FilterRequest $request
     * @return Country
     */
    public static function fromRequest(FormRequest $request): Country
    {
        $requestCountry = $request->input(self::PARAM);
        [$country] = !\is_array($requestCountry) || empty($requestCountry[0])
            ? self::DEFAULT_VALUE : $requestCountry;

        if (!is_string($country)) {
            throw new BadRequestHttpException(
                sprintf('\'%s\' parameter must be string', self::PARAM)
            );
        }

        $country = strtolower($country);

        if (!in_array($country, self::$availableParams)) {
            throw new BadRequestHttpException(sprintf(
                    '\'%s\' parameter must be one of: %s',
                    self::PARAM,
                    implode(', ', self::$availableParams)
                ));
        }
        return new static(
            $country && in_array($country, self::$availableParams)
                ? [$country] : self::DEFAULT_VALUE
        );
    }
}
