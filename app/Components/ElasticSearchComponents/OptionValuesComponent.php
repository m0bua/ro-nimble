<?php
/**
 * Класс для генерации параметра "option_values"
 * Class OptionValuesComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class OptionValuesComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->options->optionValues->getValues();
        $result = $this->elasticWrapper::DEFAULT_RESULT;

        if ($params->isEmpty()) {
            return $result;
        }

        foreach ($params as $param) {
            $values = $param[$this->filters->options->optionValues::KEY_VALUES];
            $isSingleValues = $values->count() == 1;

            if ($param[$this->filters->options->optionValues::KEY_ADDITION]) {
                if ($isSingleValues) {
                    $result[] = $this->elasticWrapper->term(Elastic::FIELD_OPTION_VALUES, $values->first());
                } else {
                    $result[] = $this->elasticWrapper->terms(Elastic::FIELD_OPTION_VALUES, $values->toArray());
                }
            } else {
                foreach ($values as $value) {
                    $result[] = $this->elasticWrapper->term(Elastic::FIELD_OPTION_VALUES, $value);
                }
            }
        }

        return $result;
    }
}
