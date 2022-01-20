<?php

namespace App\Console\Commands;

use App\Models\Eloquent\IndexGoods;
use Illuminate\Support\Facades\Artisan;

class PartialIndexing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:partial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var IndexGoods
     */
    protected IndexGoods $indexGoods;

    protected int $maxBatch;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IndexGoods $indexGoods)
    {
        parent::__construct();
        $this->indexGoods = $indexGoods;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * Execute the console command.
     *
     */
    public function proceed(): void
    {
        $query = $this->indexGoods->query()->select('id');

        foreach ($query->trueCursor($this->maxBatch) as $goods) {
            Artisan::call(IndexRefill::class, ['--goods-ids' => $goods->pluck('id')]);
            $this->indexGoods->query()->whereIn('id', $goods->pluck('id'))->delete();
        }
    }
}
