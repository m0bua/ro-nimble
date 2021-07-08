<?php

namespace Database\Seeders;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionValue;
use App\Support\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OptionValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $goodsIds = Goods::pluck('id');
        $optionIds = Option::pluck('id');

        $translatableProperties = OptionValue::make()->getTranslatableProperties();
        OptionValue::factory()
            ->count(10)
            ->create()
            ->each(static function (OptionValue $optionValue) use ($translatableProperties, $goodsIds, $optionIds) {
                foreach ($translatableProperties as $property) {
                    $optionValue->$property = [
                        Language::UK => Str::random(),
                        Language::RU => Str::random(),
                    ];
                }

                foreach ($goodsIds as $goodsId) {
                    if (in_array(rand(1, 15), [2, 8, 10, 14])) {
                        break;
                    }

                    foreach ($optionIds as $optionId) {
                        DB::table('goods_options_plural')->insertOrIgnore([
                            'goods_id' => $goodsId,
                            'option_id' => $optionId,
                            'value_id' => $optionValue->id,
                        ]);
                    }
                }
            });
    }
}
