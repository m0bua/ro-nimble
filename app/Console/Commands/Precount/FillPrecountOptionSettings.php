<?php

namespace App\Console\Commands\Precount;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PrecountOptionSetting $precountOptionSettings)
    {
        parent::__construct();
        $this->precountOptionSettings = $precountOptionSettings;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function proceed(): void
    {
        $this->precountOptionSettings->fillTable();
    }
}
