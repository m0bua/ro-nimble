<?php

namespace Database\Seeders;

use App\Models\Eloquent\Goods;
use App\Support\Language;
use Faker\Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function run()
    {
        $faker = app()->make(Generator::class);
        $translatableProperties = Goods::make()->getTranslatableProperties();
        Goods::factory()
            ->count(10)
            ->create()
            ->each(static function (Goods $goods) use ($translatableProperties, $faker) {
                foreach ($translatableProperties as $property) {
                    $goods->$property = [
                        Language::UK => $faker->word,
                        Language::RU => $faker->word,
                    ];
                }
            });
    }
}
