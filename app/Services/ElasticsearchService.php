<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ElasticsearchService
{
    /**
     * Индекс (БД) для goods
     */
    public const INDEX_GOODS = 'goods';

    /**
     * Тип (таблица) для goods
     */
    public const TYPE_GOODS = 'goods';

    /**
     * @var \Elasticsearch\Client
     */
    protected $client;

    public function __construct()
    {
        $logger = new Logger('name');
        $logger->pushHandler(new StreamHandler(
                storage_path(sprintf('logs/elastic/elastic-%s.log', date('Y-m-d')))
//                , Logger::WARNING
            )
        );

        $this->client = ClientBuilder::create()
            ->setLogger($logger)
            ->build();;
    }

    /**
     * insert or update row
     *
     * @param $parameters
     * @return array|callable
     */
    public function index($parameters)
    {
        return $this->client->index($parameters);
    }

    /**
     * delete row
     *
     * @param array $parameters
     * @return array|callable
     */
    public function delete(array $parameters)
    {
        return $this->client->delete($parameters);
    }

    /**
     * search data
     *
     * @param array $parameters
     * @return array|callable
     */
    public function search(array $parameters)
    {
        return $this->client->search($parameters)['hits'] ?? null;
    }

    /**
     * Формируем goods параметры
     *
     * @param $params
     * @return array
     */
    public function getGoodsParams($params)
    {
        return array_merge([
            'index' => ElasticsearchService::INDEX_GOODS,
            'type' => ElasticsearchService::TYPE_GOODS
        ], $params);
    }

    /**
     * insert or update good
     *
     * @param $good
     * @return array|callable
     */
    public function setGood($good)
    {
        return $this->index($this->getGoodsParams([
            'id' => $good['id'],
            'body' => $good
        ]));
    }

    /**
     * delete good
     *
     * @param $goodId
     * @return array|callable
     */
    public function removeGood($goodId)
    {
        return $this->delete($this->getGoodsParams([
            'id' => $goodId
        ]));
    }

    /**
     * search good
     *
     * @param $query
     */
    public function searchGood($search)
    {
        return $this->search($this->getGoodsParams([
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $search,
                        'fields' => ['id'],
                    ],
                ]
            ]
        ]));
    }
}
