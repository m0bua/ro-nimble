<?php

namespace App\Console\Commands;

use App\Interfaces\GoodsBuffer;
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

    /**
     * @var GoodsBuffer
     */
    private GoodsBuffer $goodsBuffer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IndexGoods $indexGoods, GoodsBuffer $goodsBuffer)
    {
        parent::__construct();
        $this->indexGoods = $indexGoods;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * Execute the console command.
     *
     */
    public function proceed(): void
    {
        foreach ($this->goodsBuffer->scan() as $goodsIds) {
            Artisan::call(IndexRefill::class, ['--goods-ids' => $goodsIds]);
        }
    }
}
