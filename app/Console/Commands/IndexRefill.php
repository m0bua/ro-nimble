<?php

namespace App\Console\Commands;

use App\Models\Elastic\Elastic;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Goods;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Message;
use Illuminate\Database\Eloquent\Collection;
use JsonException;

class IndexRefill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:refill {--goods-ids=*} {--same}';

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
    protected Goods $goods;

    /**
     * Elasticsearch goods model
     *
     * @var Elastic
     */
    protected Elastic $goodsElastic;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Goods $goods, GoodsModel $goodsElastic)
    {
        parent::__construct();
        $this->goods = $goods;
        $this->goodsElastic = $goodsElastic;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    protected function proceed(): void
    {
        $goodsIds = collect($this->option('goods-ids'));

        if ($goodsIds->isEmpty() && !$this->option('same')) {
            $this->createIndex();
        }
        $indexName = $this->goodsElastic->getIndexName();

        $query = $this->goods->query()
            ->select('id')
            ->whereNotIn('sell_status', [
                Goods::SELL_STATUS_ARCHIVE,
                Goods::SELL_STATUS_HIDDEN,
            ]);

        if ($goodsIds->isNotEmpty()) {
            $query->whereIn('id', $goodsIds->toArray());
        }

        $amqp = new Amqp();
        /** @var Collection $goods */
        foreach ($query->trueCursor($this->maxBatch) as $goods) {
            $data = [
                'index_name' => $indexName,
                'ids' => $goods->pluck('id'),
            ];

            $amqp->publish(
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
        $this->goodsElastic->createIndex(
            $this->goodsElastic->buildNewIndexName(),
            [
                'body' => [
                    'settings' => config('indices.goods.settings'),
                    'mappings' => config('indices.goods.mappings'),
                ],
            ]
        );
    }
}
