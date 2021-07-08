<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOption;
use App\Models\Eloquent\Option;
use App\ValueObjects\Options;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GoodsOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        $goodsIds = Goods::pluck('id')->toArray();
        $optionIds = Option::pluck('id')->toArray();
        $types = collect(Options::OPTIONS_BY_TYPES)->flatten()->toArray();

        return [
            'goods_id' => $goodsIds ? $faker->unique()->randomElement($goodsIds) : $faker->unique()->numberBetween(1, 1000),
            'option_id' => $optionIds ? $faker->randomElement($optionIds) : $faker->unique()->numberBetween(1, 1000),
            'type' => $faker->randomElement($types),
            'value' => $faker->words(4, true),
        ];
    }
}
