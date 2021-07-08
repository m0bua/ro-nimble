<?php

namespace Database\Seeders;

use App\Models\Eloquent\GoodsOption;
use App\Support\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GoodsOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translatableProperties = GoodsOption::make()->getTranslatableProperties();
        GoodsOption::factory()
            ->count(10)
            ->create()
            ->each(static function (GoodsOption $goodsOption) use ($translatableProperties) {
                foreach ($translatableProperties as $property) {
                    $goodsOption->$property = [
                        Language::UK => Str::random(),
                        Language::RU => Str::random(),
                    ];
                }
            });
    }
}
