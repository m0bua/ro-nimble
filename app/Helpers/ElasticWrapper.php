<?php
/**
 * Class ElasticWrapper
 * https://codedzen.ru/elasticsearch-urok-6-3-poisk/
 * https://www.elastic.co/guide/en/elasticsearch/reference/7.13/query-dsl-bool-query.html
 * @package App\Helpers
 */

namespace App\Helpers;


class ElasticWrapper
{
    /**
     * @param string $fieldAlias
     * @param string $aggsField
     * @param int|null $sizeValue
     * @return array[]
     */
    public function aggsTerms(string $fieldAlias, string $aggsField, ?int $sizeValue = null): array
    {
        return [
            $fieldAlias => [
                'terms' => array_merge(
                    ['field' => $aggsField],
                    $sizeValue ? ['size' => $sizeValue] : []
                )
            ]
        ];
    }

    /**
     * @param string $fieldAlias
     * @param string $aggsField
     * @param int|null $sizeValue
     * @return array[]
     */
    public function aggsComposite(string $fieldAlias, string $aggsField, ?int $sizeValue = null): array
    {
        return [
            $fieldAlias => [
                'composite' => array_merge(
                    ['sources' => [$this->aggsTerms($fieldAlias, $aggsField)]],
                    $sizeValue ? ['size' => $sizeValue] : []
                )
            ]
        ];
    }
}
