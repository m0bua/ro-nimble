<?php

declare(strict_types=1);

namespace App\Models\Elastic;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Elastic
 * @package App\Models\Elastic
 */
abstract class Elastic
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * Parameters for query
     *
     * @var array
     */
    private array $params;

    /**
     * @var array
     */
    private array $attributes = [];

    private string $tmpOldIndexName = 'goods_index_a';

    /**
     * Elastic constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('database.elasticsearch.hosts'))
            ->setBasicAuthentication(
                config('database.elasticsearch.basic_auth.username'),
                config('database.elasticsearch.basic_auth.password')
            )
            ->build();
    }

    abstract public function indexPrefix(): string;

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Возвращает актуальное имя индекса
     *
     * @return string
     */
    public function getIndexName(): string
    {
        return $this->indexInfo()->pluck('index')->first() ?? '';
    }

    /**
     * Возвращает имя индекса у которого есть алиас
     *
     * @return string
     */
    public function getIndexWithAlias(): string
    {
        return collect(
            $this->client
                ->cat()
                ->aliases()
        )->filter(function($item) {
            return $item['alias'] === $this->indexPrefix();
        })
            ->sortByDesc('index')
            ->pluck('index')
            ->first() ?? '';
    }

    /**
     * Возвращает информацию об актуальном индексе
     *
     * @param string|null $index
     * @return array
     */
    public function indexInfo(?string $index = null): Collection
    {
        $index = $index ?? $this->indexPrefix() . '_*';

        return collect(
            $this->client
                ->cat()
                ->indices(['index' => $index])
        )
            ->sortByDesc('index');
    }

    /**
     * @return string
     */
    public function buildNewIndexName(): string
    {
        $lastVersion = (int)Str::afterLast($this->getIndexName(), '_');

        return $this->indexPrefix() . '_' . ++$lastVersion;
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function search(array $params = [])
    {
        return $this->prepareParams($params)->client->search($this->params);
    }

    /**
     * @param array $params
     * @return array|callable
     * @throws Exception
     */
    public function index(array $params = [])
    {
        return $this->prepareParams(array_merge(['body' => $this->getAttributes()], $params))->client->index($this->params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function update(array $params = [])
    {
        return $this->client->update($params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function reindex(array $params = [])
    {
        return $this->client->reindex($params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function scroll(array $params = [])
    {
        return $this->client->scroll($params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function bulk(array $params = [])
    {
        return $this->client->bulk($params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function delete(array $params = [])
    {
        $this->prepareParams($params);

        if ($this->client->exists($this->params)) {
            return $this->client->delete($this->params);
        }

        return [];
    }

    /**
     * Get entities by ids from provided index
     *
     * @param string $index
     * @param array $ids
     * @param bool $withSource
     * @return array|callable
     */
    public function mget(string $index, array $ids, bool $withSource = true)
    {
        return $this->client->mget([
            'index' => $index,
            '_source' => $withSource,
            'body' => [
                'ids' => $ids
            ],
        ]);
    }

    /**
     * Возвращает source-результат поиска
     *
     * @param array $searchResult
     * @return array
     */
    public function getSource(array $searchResult): array
    {
        if (!isset($searchResult['hits'])) {
            return array_column($searchResult, '_source');
        }

        return $this->getSource($searchResult['hits']);
    }

    /**
     * TODO Needs refactor
     *
     * @param array $searchResult
     * @return array
     */
    public function one(array $searchResult): array
    {
        return $searchResult[0] ?? $searchResult;
    }

    /**
     * TODO Needs refactor
     *
     * @param array $searchResult
     * @return array|array[]
     */
    public function all(array $searchResult): array
    {
        return !isset($searchResult[0]) ? [$searchResult] : $searchResult;
    }

    /**
     * @param string $attributeName
     * @return mixed
     * @throws Exception
     */
    public function getAttribute(string $attributeName)
    {
        if (!isset($this->attributes[$attributeName])) {
            throw new Exception("Attribute $attributeName not found");
        }

        return $this->attributes[$attributeName];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $params
     * @return $this
     */
    private function prepareParams(array $params = []): self
    {
        $this->params = array_merge(['index' => $this->getIndexName()], $params);

        return $this;
    }

    /**
     * Создание нового индекса
     * @param string $name
     * @param array $params
     * @return array
     */
    public function createIndex(string $name, array $params): array
    {
        if ($this->existIndex($name)) {
            return [];
        }

        return $this->client->indices()->create(array_merge(['index' => $name], $params));
    }

    /**
     * Удаление индекса
     * @param $name
     * @return array
     */
    public function deleteIndex($name): array
    {
        if (!$name || !$this->existIndex($name)) {
            return [];
        }

        return $this->client->indices()->delete([
            'index' => $name
        ]);
    }

    /**
     * Проверка на существование индекса
     * @param string $name
     * @return bool
     */
    public function existIndex(string $name): bool
    {
        return $this->client->indices()->exists([
            'index' => $name
        ]);
    }

    /**
     * Массовое обновление алиасов
     * @param array $actions
     * @return array
     */
    public function updateAliases(array $actions): array
    {
        return $this->client->indices()->updateAliases([
            'body' => [
                'actions' => array_values(array_filter($actions))
            ]
        ]);
    }

    /**
     * Генерирует поведение для добавления алиаса
     * @param string $index
     * @param string|null $alias
     * @return array
     */
    public function addAliasAction(string $index, string $alias): array
    {
        if (!$index || !$this->existIndex($index)) {
            return [];
        }

        return [
            'add' => [
                'index' => $index,
                'alias' => $alias
            ]
        ];
    }

    /**
     * @param string $index
     * @param string|null $alias
     * @return array
     */
    public function removeAliasAction(string $index, string $alias): array
    {
        if (!$index || !$this->existIndex($index)) {
            return [];
        }

        return [
            'remove' => [
                'index' => $index,
                'alias' => $alias
            ]
        ];
    }

    /**
     * @return array
     */
    public function requiredFields(): array
    {
        return [];
    }
}
