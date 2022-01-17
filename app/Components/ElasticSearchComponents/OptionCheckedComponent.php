<?php
/**
 * Класс для генерации параметра "option_checked"
 * Class OptionCheckedComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class OptionCheckedComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->options->optionChecked->getValues();

        if ($params->count() > 1) {
            return $this->elasticWrapper->terms(Elastic::FIELD_OPTION_CHECKED, $params->toArray());
        } else {
            return $this->elasticWrapper->term(Elastic::FIELD_OPTION_CHECKED, $params->first());
        }
    }
}
