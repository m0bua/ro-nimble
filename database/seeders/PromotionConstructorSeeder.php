<?php

namespace Database\Seeders;

use App\Models\Eloquent\PromotionConstructor;
use Illuminate\Database\Seeder;

class PromotionConstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromotionConstructor::factory()->count(50)->create();
    }
}
