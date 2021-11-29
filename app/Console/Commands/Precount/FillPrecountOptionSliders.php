<?php

namespace App\Console\Commands\Precount;

use App\Models\Eloquent\PrecountOptionSlider;
use App\Console\Commands\Command;

class FillPrecountOptionSliders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'precount:option-sliders-fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill precount_option_sliders table';

    private PrecountOptionSlider $precountOptionSlider;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PrecountOptionSlider $precountOptionSlider)
    {
        parent::__construct();
        $this->precountOptionSlider = $precountOptionSlider;
    }

    public function proceed(): void
    {
        $this->precountOptionSlider->fillTable();
    }
}
