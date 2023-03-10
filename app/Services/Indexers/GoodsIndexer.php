<?php

namespace App\Services\Indexers;

use App\Models\Elastic\GoodsModel;
use App\Services\Indexers\Aggregators\Aggregator;
use App\Services\Indexers\Aggregators\Goods\GoodsAggregator;
use Illuminate\Support\Collection;
use JsonException;
use PhpAmqpLib\Message\AMQPMessage;

class GoodsIndexer implements Indexer
{
    /**
     * Elasticsearch goods model
     *
     * @var GoodsModel
     */
    private GoodsModel $elastic;

    /**
     * Goods data aggregator
     *
     * @var Aggregator
     */
    private Aggregator $goods;

    /**
     * Elasticsearch destination index names
     *
     * @var array
     */
    private array $indexes;

    /**
     * Goods IDs collection
     *
     * @var Collection
     */
    private Collection $ids;

    /**
     * @param GoodsModel $elastic
     * @param GoodsAggregator $goods
     */
    public function __construct(GoodsModel $elastic, GoodsAggregator $goods)
    {
        $this->elastic = $elastic;
        $this->goods = $goods;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function handleMessage(AMQPMessage $message): void
    {
        $this->setup($message);

        $payload = $this->preparePayload($this->ids);
        $this->elastic->bulk($payload);

        $message->ack();
    }

    /**
     * Prepare payload for Elasticsearch
     *
     * @param Collection $ids
     * @return array
     */
    private function preparePayload(Collection $ids): array
    {
        $data = [];
        $goods = $this->goods->aggregate($ids);
        $indexIds = [];

        foreach ($goods->all() as $item) {
            foreach ($this->indexes as $index) {
                $indexIds[] = $item->id;
                $data[] = ['index' => [
                    '_index' => $index,
                    '_id' => $item->id,
                ]];
                $data[] = $item;
            }
        }

        $deleteIds = \array_diff($ids->toArray(), $indexIds);
        foreach ($deleteIds as $id) {
            foreach ($this->indexes as $index) {
                $data[] = ['delete' => [
                    '_index' => $index,
                    '_id' => $id,
                ]];
            }
        }

        return ['body' => $data];
    }

    /**
     * Set index name and IDs from message
     *
     * @param AMQPMessage $message
     * @return void
     * @throws JsonException
     */
    private function setup(AMQPMessage $message): void
    {
        $body = json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->indexes = $body['index_names'];
        $this->ids = collect($body['ids']);
    }
}
