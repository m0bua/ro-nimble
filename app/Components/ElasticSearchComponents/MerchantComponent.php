<?php
/**
 * Класс для генерации параметра "merchant_id"
 * Class MerchantComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class MerchantComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->terms(Elastic::FIELD_MERCHANT, $this->filters->sellers->getValues()->toArray());
    }
}
