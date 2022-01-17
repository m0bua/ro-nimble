<?php

namespace App\Console\Commands\Dev;

use App\Models\Eloquent\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        App::setLocale('uk');
        $q = Category::query()
            ->select(['id'])
            ->whereIn('id', [1, 2, 3])
            ->selectTranslations(['title'])
            ->get()
            ->toArray();

//        $q = Category::query()
//            ->select(['categories.id'])
//            ->leftJoin('category_options as co', 'categories.id', 'co.category_id')
//            ->selectNestedTranslations('category_option_translations', 'co.id', 'category_option_id', 'value')
//            ->toBase()
//            ->get();

        return 0;
    }
}
