<?php
/**
 * Класс для генерации параметра "option_sliders"
 * Class OptionSlidersComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class OptionSlidersComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->options->optionSliders->getValues();
        $result = $this->elasticWrapper::DEFAULT_RESULT;

        if ($params->isEmpty()) {
            return $result;
        }

        foreach ($params as $optionId => $values) {
            $result[] = $this->elasticWrapper->nested(
                Elastic::FIELD_OPTION_SLIDERS,
                $this->elasticWrapper->bool(
                    $this->elasticWrapper->filter([
                        $this->elasticWrapper->term(Elastic::FIELD_OPTION_SLIDERS_ID, $optionId),
                        $this->elasticWrapper->range(Elastic::FIELD_OPTION_SLIDERS_VALUE, [
                            $this->elasticWrapper::RANGE_GTE => $values[$this->filters->options->optionSliders::MIN_KEY],
                            $this->elasticWrapper::RANGE_LTE => $values[$this->filters->options->optionSliders::MAX_KEY]
                        ])
                    ])
                )
            );
        }

        return $result;
    }
}
