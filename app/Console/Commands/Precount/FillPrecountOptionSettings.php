<?php

namespace App\Console\Commands\Precount;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\PrecountOptionSetting;
use App\Console\Commands\Command;
use Illuminate\Support\Facades\DB;

class FillPrecountOptionSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'precount:option-settings-fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill precount_option_settings table';
    private PrecountOptionSetting $precountOptionSettings;
    private Category $category;
    private Option $option;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PrecountOptionSetting $precountOptionSettings, Category $category)
    {
        parent::__construct();
        $this->precountOptionSettings = $precountOptionSettings;
        $this->category = $category;
        $this->option = app()->make(Option::class);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function proceed(): void
    {
        DB::table('precount_option_settings')->update(['is_deleted' => 1]);
        $specificOptions = array_merge($this->option->getSpecificOptions(), [26294, 218618]);

        foreach ($this->category->getMainCategoriesIds() as $categoryId) {
            $this->precountOptionSettings->fillTable($categoryId, $specificOptions);
        }

        DB::table('precount_option_settings')->where(['is_deleted' => 1])->delete();
    }
}
