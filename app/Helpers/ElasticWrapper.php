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
    public const DEFAULT_RESULT = [];

    public const RANGE_GTE = 'gte'; // >=
    public const RANGE_GT = 'gt'; // >
    public const RANGE_LTE = 'lte'; // <=

    /**
     * @param $params
     * @return array
     */
    public function body($params): array
    {
        return ['body' => $this->prepareMultiParams($params)];
    }

    /**
     * @param $params
     * @return array
     */
    public function query($params): array
    {
        return ['query' => $params];
    }

    /**
     * @param array $params
     * @return array
     */
    public function bool(array $params): array
    {
        return ['bool' => $this->prepareMultiParams($params)];
    }

    /**
     * @param array $params
     * @return array
     */
    public function filter(array $params): array
    {
        return ['filter' => array_values(array_filter($params))];
    }

    /**
     * @param $params
     * @return array
     */
    public function must($params): array
    {
        return ['must' => array_values(array_filter($params))];
    }

    /**
     * @param $params
     * @return array
     */
    public function mustNot($params): array
    {
        return ['must_not' => array_values(array_filter($params))];
    }

    /**
     * @param array $params
     * @return array
     */
    public function should(array $params): array
    {
        return ['should' => array_values(array_filter($params))];
    }

    /**
     * @param string $field
     * @param $param
     * @return array[]
     */
    public function term(string $field, $param): array
    {
        return $param || $param === 0 ? ['term' => [$field => $param]] : self::DEFAULT_RESULT;
    }

    /**
     * @param string $field
     * @param $param
     * @return array[]
     */
    public function terms(string $field, array $param): array
    {
        if (!$param) {
            return self::DEFAULT_RESULT;
        }

        return [
            'terms' => [
                $field => $param
            ]];
    }

    /**
     * @param string $field
     * @param array $params
     * @return \array[][]
     */
    public function range(string $field, array $params): array
    {
        return [
            'range' => [
                $field => $params
            ]];
    }

    /**
     * @param string $path
     * @param array $boolQuery
     * @return array[]
     */
    public function nested(string $path, array $boolQuery): array
    {
        return [
            'nested' => [
                'path' => $path,
                'query' => $boolQuery
            ]
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function aggs(array $params): array
    {
        return [
            'aggs' => $params
        ];
    }

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

    public function count()
    {
        return [
            'types_count' => [
                'value_count' => [
                    'field' => 'id'
                ]
            ]
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function prepareMultiParams(array $params): array
    {
        if (count($params) > 1) {
            $preparedParams = [];

            foreach ($params as $param) {
                $preparedParams = array_merge($preparedParams, $param);
            }

            return $preparedParams;
        }

        return $params;
    }

    /**
     * @param array $data
     * @param string $field
     * @return array
     */
    public function getUniqueFieldData(array $data, string $field): array
    {
        return array_values(array_unique(array_column(array_column($data['hits']['hits'], '_source'), $field)));
    }

    /**
     * @param array $data
     * @param string $aggrKey
     * @return array
     */
    public function prepareAggrData(array $data, string $aggrKey): array
    {
        $result = [];

        $data = $data['aggregations'][$aggrKey];

        if (empty($data['buckets']) || !is_array($data['buckets'])) {
            return $result;
        }

        foreach ($data['buckets'] as $bucket) {
            $result[$bucket['key']] = $bucket['doc_count'];
        }

        return $result;
    }

    /**
     * @param array $data
     * @param string $aggrKey
     * @return array
     */
    public function prepareAggrCompositeData(array $data, string $aggrKey): array
    {
        $result = [];

        $data = $data['aggregations'][$aggrKey];

        if (empty($data['buckets']) || !is_array($data['buckets'])) {
            return $result;
        }

        foreach ($data['buckets'] as $bucket) {
            $result[$bucket['key'][$aggrKey]] = $bucket['doc_count'];
        }

        return $result;
    }

    /**
     * @param array $data
     * @return int
     */
    public function prepareCountAggrData(array $data): int
    {
        return $data['aggregations']['types_count']['value'] ?? 0;
    }
}
