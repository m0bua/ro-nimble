<?php

namespace App\Console\Commands\Indexing;

use App\Console\Commands\Command;
use App\Models\Elastic\GoodsModel;

class Services extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do some service things with elastic indices (upd aliases, remove indices etc.)';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $goodsElastic;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GoodsModel $goodsElastic)
    {
        $this->goodsElastic = $goodsElastic;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function proceed(): void
    {
        $activeIndexName = $this->goodsElastic->getIndexWithAlias();
        $lastIndexInfo = $this->goodsElastic->indexInfo()->first();

        if ($lastIndexInfo && $activeIndexName !== $lastIndexInfo['index']) {
            $activeIndexInfo = $this->goodsElastic->indexInfo($activeIndexName)->first();

            $activeCount = (int)$activeIndexInfo['docs.count'];
            $lastCount = (int)$lastIndexInfo['docs.count'];
            $percentageDiff = ($activeCount !== 0)
                ? ($activeCount - $lastCount) / $activeCount * 100
                : 0;

            if ($percentageDiff < 10 && $lastIndexInfo['status'] === 'open' && $lastIndexInfo['health'] = 'green') {
                $this->goodsElastic->updateAliases([
                    $this->goodsElastic->addAliasAction($lastIndexInfo['index'], $this->goodsElastic->indexPrefix()),
                    $this->goodsElastic->removeAliasAction($activeIndexName, $this->goodsElastic->indexPrefix()),
                ]);

                $this->goodsElastic->deleteIndex($activeIndexName);
            }
        }
    }
}
