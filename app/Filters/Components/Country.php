<?php

namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

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
 *             enum={"ua","uz"},
 *             type="string"
 *         )
 *     )
 * ),
 */
class Country extends AbstractFilter
{
    public const DEFAULT_VALUE = [Filters::COUNTRY_UA];

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
        $requestCountry = $request->input(Filters::PARAM_COUNTRY);
        if (!\is_array($requestCountry) || empty($requestCountry[0])) {
            $country = self::DEFAULT_VALUE;
        } else {
            $country = strtolower($requestCountry[0]);
        }

        return new static(
            $country && in_array($country, self::$availableParams) ? [$country] : self::DEFAULT_VALUE
        );
    }
}
