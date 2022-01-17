<?php
/**
 * Класс для работы с фильтром "Код страны"
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        $country = strtolower($request->input(Filters::PARAM_COUNTRY));

        return new static(
            $country && in_array($country, self::$availableParams) ? [$country] : self::DEFAULT_VALUE
        );
    }
}
