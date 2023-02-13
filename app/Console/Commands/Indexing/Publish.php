<?php

namespace App\Console\Commands\Indexing;

use App\Console\Commands\Command;
use App\Models\Elastic\Elastic;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Indices;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Exception\Configuration;
use Bschmitt\Amqp\Message;
use Illuminate\Database\Eloquent\Collection;
use JsonException;

class Publish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:refill {--goods-ids=*} {--same} {--is_partial}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new index if needs and send goods ids to queue';

    /**
     * Maximum count goods for one bulk indexing
     */
    protected int $maxBatch;

    /**
     * Goods model
     *
     * @var Goods
     */
    private Goods $goods;

    /**
     * Elasticsearch goods model
     *
     * @var Elastic
     */
    private Elastic $elastic;

    /**
     * Indices model
     *
     * @var Indices
     */
    private Indices $indices;

    /**
     * Create a new command instance.
     *
     * @return void
     * @noinspection LaravelFunctionsInspection
     */
    public function __construct(Goods $goods, GoodsModel $elastic, Indices $indices)
    {
        parent::__construct();
        $this->goods = $goods;
        $this->indices = $indices;
        $this->elastic = $elastic;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @inheritDoc
     * @throws JsonException|Configuration
     */
    protected function proceed(): void
    {
        $this->indices->cleanUp($this->elastic->indexInfo()->pluck('index')->all());
        $goodsIds = collect($this->option('goods-ids'));

        if ($goodsIds->isEmpty() && !$this->option('same')) {
            $this->createIndex();
        }
        $indexNames = $this->option('is_partial')
            ? $this->elastic->getIndexNames()
            : [$this->elastic->getIndexName()];

        $query = $this->goods->query()
            ->select('id')
            ->whereNotIn('sell_status', [
                Goods::SELL_STATUS_ARCHIVE,
                Goods::SELL_STATUS_HIDDEN,
            ]);

        if ($goodsIds->isNotEmpty()) {
            $query->whereIn('id', $goodsIds->toArray());
        }

        $rabbitMq = new Amqp();

        /** @var Collection|Goods[] $goods */
        foreach ($query->trueCursor($this->maxBatch) as $goods) {
            $data = [
                'index_names' => $indexNames,
                'ids'         => $goods->pluck('id')
            ];

            $rabbitMq->publish(
                'indexing.goods.ids',
                new Message(json_encode($data, JSON_THROW_ON_ERROR)),
                config('amqp.properties.local')
            );
        }
    }

    /**
     * Create new index with new generated name
     * @return void
     */
    protected function createIndex(): void
    {
        $this->elastic->createIndex(
            $this->elastic->buildNewIndexName(),
            [
                'body' => [
                    'settings' => config('indices.goods.settings'),
                    'mappings' => config('indices.goods.mappings'),
                ],
            ]
        );
    }
}
