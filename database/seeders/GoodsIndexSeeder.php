<?php

namespace Database\Seeders;

use DB;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GoodsIndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = new Generator();
        for ($i = 0; $i < 150000; $i++) {
            DB::table('elastic_goods_index')->insert([
                'id' => $i + 1,
                'category_id' => $faker->numberBetween(),
                'group_id' => $faker->numberBetween(),
                'is_group_primary' => $faker->numberBetween(0, 1),
                'option_checked' => $faker->numberBetween(),
                'seller_id' => $faker->numberBetween(10, 100),
                'seller_order' => $faker->numberBetween(0, 10),
                'series_id' => $faker->numberBetween(100, 1000),
                'order' => $faker->numberBetween(100, 1000),
                'pl_bonus_charge_pcs' => $faker->numberBetween(100, 1000),
                'price' => $faker->numberBetween(100, 1000),
                'producer_id' => $faker->numberBetween(100, 1000),
                'search_rank' => $faker->randomFloat(1, 10),
                'sell_status' => Str::random(),
                'producer_title' => Str::random(),
                'state' => Str::random(),
                'status_inherited' => Str::random(),
                'country_code' => Str::random(),
                'group_token' => Str::random(),
                'promotion_constructors' => serialize(array_fill(0, 15, [
                    'id' => $faker->numberBetween(),
                    'gift_id' => $faker->numberBetween(),
                    'promotion_id' => $faker->numberBetween(),
                ])),
                'option_sliders' => serialize(array_fill(0, 15, [
                    'id' => $faker->numberBetween(),
                    'name' => Str::random(),
                    'value' => $faker->randomFloat(2),
                ])),
                'option_values' => serialize([123,312,4324,52523,24234]),
                'options' => serialize([5244523,245234,23452345,23452345,2352435]),
                'tags' => serialize([52435,3252,2345234,2345235,23452,235234,234523,32452]),
            ]);
        }
    }
}
