<?php

namespace App\Console\Commands\Precount;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\PrecountOptionSetting;
use App\Console\Commands\Command;

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
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function proceed(): void
    {
        foreach ($this->category->getMainCategoriesIds() as $categoryId) {
            $this->precountOptionSettings->fillTable($categoryId);
        }
    }
}
