<?php

namespace App\Console\Commands;

use App\Models\Elastic\GoodsModel;

class IndexServices extends Command
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
     * Execute the console command.
     *
     */
    public function proceed(): void
    {
        dd($this->goodsElastic->getClient()->cat()->indices());
    }
}
