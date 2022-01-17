<?php
/**
 * Класс для генерации параметра "options"
 * Class OptionsComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class OptionsComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->options->optionValues->getValues()->keys();
        $result = $this->elasticWrapper::DEFAULT_RESULT;

        if ($params->isEmpty()) {
            return $result;
        }

        foreach ($params as $param) {
            $result[] = $this->elasticWrapper->term(Elastic::FIELD_OPTIONS, $param);
        }

        return $result;
    }
}
