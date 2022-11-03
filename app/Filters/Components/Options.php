<?php
/**
 * Класс для работы с динамичными фильтрами
 */
namespace App\Filters\Components;

use App\Enums\Filters;
use App\Filters\Components\Options\OptionChecked;
use App\Filters\Components\Options\OptionSliders;
use App\Filters\Components\Options\OptionValues;
use App\Http\Requests\FilterRequest;
use App\Models\Eloquent\Option;
use Illuminate\Foundation\Http\FormRequest;
use Exception;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Options extends AbstractFilter
{
    /**
     * @var OptionValues
     */
    public OptionValues $optionValues;

    /**
     * @var OptionChecked
     */
    public OptionChecked $optionChecked;

    /**
     * @var OptionSliders
     */
    public OptionSliders $optionSliders;

    /**
     * Список категорий в рамках которых будут выводиться опции
     * (зависит от фильтра "Дерево категорий")
     * @var array
     */
    protected array $optionCategories = Filters::DEFAULT_FILTER_VALUE;

    /**
     * @var string
     */
    protected string $name = Filters::OPTIONS;

    /**
     * @var array
     */
    protected array $values = Filters::DEFAULT_FILTER_VALUE;

    /**
     * Options constructor.
     * @param array $values
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => $attribute) {
            if (!property_exists($this, $name)) {
                throw new Exception("Unknown property $name");
            }

            $this->$name = $attribute;
        }
    }

    /**
     * @param FilterRequest $request
     * @return Options
     */
    public static function fromRequest(FormRequest $request): Options
    {
        $dynamicOptions = array_diff_key($request->all(), array_flip(Filters::$staticFiltersParams));

        if (empty($dynamicOptions)) {
            return self::initAttributes(Filters::DEFAULT_FILTER_VALUE);
        }

        $options = Option::getOptsByNames(array_map('strval', array_keys($dynamicOptions)));

        if (!$options->count()) {
            return self::initAttributes(Filters::DEFAULT_FILTER_VALUE);
        }

        $params = [];

        foreach ($options as $option) {
            switch (true) {
                case $option->type === Option::TYPE_CHECKBOX:
                    if (!is_string($dynamicOptions[$option->name])) {
                        throw new BadRequestHttpException(
                            sprintf('\'%s\' parameter must be string', $option->name)
                        );
                    }

                    $params[Filters::OPTION_CHECKED][$option->id] = $dynamicOptions[$option->name];
                    break;
                case in_array($option->type, Option::$sliderTypes):
                    if (!is_string($dynamicOptions[$option->name])) {
                        throw new BadRequestHttpException(
                            sprintf('\'%s\' parameter must be string', $option->name)
                        );
                    }

                    $params[Filters::OPTION_SLIDERS][$option->id] = $dynamicOptions[$option->name];
                    break;
                default:
                    if (!is_array($dynamicOptions[$option->name])) {
                        throw new BadRequestHttpException(
                            sprintf('\'%s\' parameter must be array', $option->name)
                        );
                    }

                    $params[Filters::OPTION_VALUES][$option->id] = [
                        'option' => $option,
                        'optionValues' => $dynamicOptions[$option->name]
                    ];
            }
        }

        return self::initAttributes($params);
    }

    /**
     * @param array $params
     * @return static
     * @throws Exception
     */
    private static function initAttributes(array $params) {
        $attributes = array_flip(Filters::$dynamicFiltersAttributes);

        foreach ($attributes as $key => &$attribute) {
            $className = __NAMESPACE__ . '\\Options\\' . ucfirst($key);
            if (!class_exists($className)) {
                throw new Exception("Missed class for $key attribute");
            } elseif (!method_exists($className, 'fromRequest')) {
                throw new Exception("Unable to create $className from Request");
            }

            $attribute = $className::fromRequest(!empty($params[$key]) ? $params[$key] : Filters::DEFAULT_FILTER_VALUE);
        }

        return new static($attributes);
    }

    /**
     * Hide all options values
     */
    public function hideValues(): void
    {
        $this->optionValues->hideValues();
        $this->optionChecked->hideValues();
        $this->optionSliders->hideValues();
    }

    /**
     * Show all options values
     */
    public function showValues(): void
    {
        $this->optionValues->showValues();
        $this->optionChecked->showValues();
        $this->optionSliders->showValues();
    }

    /**
     * @param array $optionCategories
     */
    public function setOptionCategories(array $optionCategories)
    {
        $this->optionCategories = $optionCategories;
    }

    /**
     * @param array $optionCategories
     */
    public function addOptionCategories(array $optionCategories)
    {
        $this->optionCategories = array_values(array_unique(array_merge($this->optionCategories, $optionCategories)));
    }

    /**
     * @return array
     */
    public function getOptionCategories(): array
    {
        return $this->optionCategories;
    }

    /**
     * Returns filter values
     * @return Collection
     */
    public function getValues(): Collection
    {
        return $this->optionValues->getValues()
            ->merge($this->optionChecked->getValues())
            ->merge($this->optionSliders->getValues());
    }
}
