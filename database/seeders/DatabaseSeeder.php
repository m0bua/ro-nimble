<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GoodsSeeder::class,
            ProducerSeeder::class,
            OptionSeeder::class,
            GoodsOptionSeeder::class,
            OptionValueSeeder::class,
            PromotionConstructorSeeder::class,
            PromotionGoodsConstructorSeeder::class,
            PromotionGroupConstructorSeeder::class,
        ]);
    }
}
