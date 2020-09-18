<?php


namespace App\Console\Commands;


use App\Models\Elastic\GoodsModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteMarkedProducersCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-marked-producers';

    /**
     * @var string
     */
    protected $description = 'Delete marked producers in DB';

    /**
     *
     */
    public function handle()
    {
        DB::table('producers')->where(['is_deleted' => 1])->delete();
    }
}
