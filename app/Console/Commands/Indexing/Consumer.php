<?php

namespace App\Console\Commands\Indexing;

use App\Models\Elastic\Elastic;
use App\Models\Elastic\GoodsModel;
use App\Services\Indexers\GoodsIndexer;
use App\Services\Indexers\Indexer;
use Bschmitt\Amqp\Amqp;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer extends Command
{
    protected const CONFIG = 'amqp.properties.local';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:index {queue?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer for indexing goods data';

    /**
     * @var string
     */
    protected string $indexName;

    /**
     * @var Elastic
     */
    protected Elastic $goodsElastic;

    /**
     * Indexer service
     *
     * @var Indexer
     */
    private Indexer $indexer;

    /**
     * @var Collection
     */
    protected Collection $goodsIds;

    /**
     * @var array
     */
    protected array $indexBody = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GoodsModel $goodsElastic, GoodsIndexer $indexer)
    {
        $this->goodsElastic = $goodsElastic;
        $this->indexer = $indexer;

        parent::__construct();
    }

    /**
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $queue =  $this->argument('queue') ?? config(self::CONFIG . '.queue');

        $rabbitMq = new Amqp();
        $rabbitMq->consume($queue, function (AMQPMessage $message) {
            $this->indexer->handleMessage($message);
        }, config(self::CONFIG));

        return 0;
    }
}
