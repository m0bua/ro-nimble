<?php

namespace Database\Seeders;

use App\Models\Eloquent\PromotionGoodsConstructor;
use Illuminate\Database\Seeder;

class PromotionGoodsConstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromotionGoodsConstructor::factory()->count(40)->create();
    }
}
