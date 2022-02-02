<?php
/**
 * Класс для генерации параметра "query"
 * Class QueryComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class QueryComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $param = $this->filters->query->getValues()->first();

        if (!$param) {
            return $this->elasticWrapper::DEFAULT_RESULT;
        }

        return $this->elasticWrapper->bool($this->elasticWrapper->should([
            $this->elasticWrapper->term(Elastic::FIELD_PRODUCER_TITLE_TEXT, $param),
            $this->elasticWrapper->term(Elastic::FIELD_PRODUCER_TITLE_KEYWORD, $param)
        ]));
    }
}
