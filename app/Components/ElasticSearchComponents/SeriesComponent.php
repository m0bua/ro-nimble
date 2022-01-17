<?php
/**
 * Класс для генерации параметра "series_id"
 * Class SeriesComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class SeriesComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->series->getValues();

        if ($params->count() > 1) {
            return $this->elasticWrapper->terms(Elastic::FIELD_SERIES, $params->toArray());
        } else {
            return $this->elasticWrapper->term(Elastic::FIELD_SERIES, $params->first());
        }
    }
}
