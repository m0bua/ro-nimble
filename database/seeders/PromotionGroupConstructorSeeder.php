<?php

namespace Database\Seeders;

use App\Models\Eloquent\PromotionGroupConstructor;
use Illuminate\Database\Seeder;

class PromotionGroupConstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromotionGroupConstructor::factory()->count(15)->create();
    }
}
