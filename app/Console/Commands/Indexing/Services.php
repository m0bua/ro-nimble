<?php

namespace App\Console\Commands\Indexing;

use App\Console\Commands\Command;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Indices;

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
        $goodsIndices = $this->goodsElastic->indexInfo()->map(function ($item) {
            $item['db_status'] = Indices::getStatus($item['index']);
            return $item;
        });

        $newestIndex = $olderIndex = null;
        $activeIndex = $goodsIndices->filter(function ($item) use ($activeIndexName) {
            return $item['index'] === $activeIndexName;
        })->first();
        foreach ($goodsIndices as $index) {
            if ($index['index'] === $activeIndexName) {
                $newestIndex = null === $newestIndex ? $activeIndex : null;
                continue;
            }
            if (Indices::STATUS_ACTIVE === $index['db_status'] && null === $newestIndex) {
                $newestIndex = $index;
                $olderIndex = $activeIndex;
                break;
            }

            if (Indices::STATUS_ACTIVE === $index['db_status'] && null !== $newestIndex) {
                $olderIndex = $index;
                break;
            }
        }
        $deleteIndices = $goodsIndices->filter(function ($item) use ($newestIndex, $olderIndex) {
            return !($item['index'] === $newestIndex['index']
                || (null !== $olderIndex && $item['index'] === $olderIndex['index'])
                || $item['db_status'] === Indices::STATUS_LOCKED);
        })->pluck('index')->toArray();

        if (null !== $activeIndex && $activeIndex['index'] !== $newestIndex['index']) {
            $currentCount = (int) $activeIndex['docs.count'];
            $newestCount = (int) $newestIndex['docs.count'];
            $percentageDiff = ($currentCount !== 0)
                ? ($currentCount - $newestCount) / $currentCount * 100
                : 0;

            if ($percentageDiff < 10 && $newestIndex['status'] === 'open' && $newestIndex['health'] = 'green') {
                $this->goodsElastic->updateAliases([
                    $this->goodsElastic->addAliasAction($newestIndex['index'], $this->goodsElastic->indexPrefix()),
                    $this->goodsElastic->removeAliasAction($activeIndex['index'], $this->goodsElastic->indexPrefix()),
                ]);
            }
        }

        if (null === $activeIndex) {
            $this->goodsElastic->updateAliases([
                $this->goodsElastic->addAliasAction($newestIndex['index'], $this->goodsElastic->indexPrefix()),
            ]);
        }

        foreach ($deleteIndices as $index) {
            $this->goodsElastic->deleteIndex($index);
        }
    }
}
