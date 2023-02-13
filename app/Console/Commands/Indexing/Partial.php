<?php

namespace App\Console\Commands\Indexing;

use App\Console\Commands\Command;
use App\Interfaces\GoodsBuffer;

class Partial extends Command
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
    protected $description = 'Partial indexing from buffer';

    /**
     * @var GoodsBuffer
     */
    private GoodsBuffer $goodsBuffer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GoodsBuffer $goodsBuffer)
    {
        parent::__construct();
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * Execute the console command.
     *
     */
    public function proceed(): void
    {
        foreach ($this->goodsBuffer->scan() as $goodsIds) {
            if (!empty($goodsIds)) {
                $this->call(Publish::class, ['--goods-ids' => $goodsIds, '--is_partial' => true]);
            }
        }
    }
}
