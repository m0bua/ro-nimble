<?php

namespace App\Console\Commands\Dev;

use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for testing everything';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $message = '{"fields_data":{"id":1000014,"promotion_id":1000022,"gift_id":1,"title":"Название акции TEST","title_uk":"Название акции (Ua) TEST","description":"Краткое содержание TEST","description_uk":"Краткое содержание (Ua) TEST","show_on_accessories_page":0,"country_code":"uz"},"changed_fields":{"id":1000014,"promotion_id":1000022,"gift_id":1,"title":"Название акции TEST","title_uk":"Название акции (Ua) TEST","description":"Краткое содержание TEST","description_uk":"Краткое содержание (Ua) TEST","show_on_accessories_page":0,"country_code":"uz"},"timestamp":1617624167}';
        $count = (int)$this->ask('How many messages are need to publish?');

        for ($i = 0; $i < $count; $i++) {
            Amqp::publish('change.Promotion_Constructor.record', $message);
        }

        Log::info("$count messages published");
        $this->info("$count messages published");

        return 0;
    }
}
