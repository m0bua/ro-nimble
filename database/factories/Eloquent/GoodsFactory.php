<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Goods::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'id' => $faker->unique()->numberBetween(1, 100000),
            'name' => $faker->word,
            'category_id' => $faker->numberBetween(1, 1000),
            'mpath' => $faker->word,
            'price' => $faker->randomFloat(),
            'rank' => $faker->numberBetween(1, 10),
            'sell_status' => 'available',
            'producer_id' => $faker->numberBetween(1, 1000),
            'seller_id' => $faker->numberBetween(1, 1000),
            'group_id' => $faker->numberBetween(1, 1000),
            'is_group_primary' => (int)$faker->boolean(),
            'status_inherited' => $faker->word,
            'order' => $faker->numberBetween(1, 10),
            'series_id' => $faker->numberBetween(1, 1000),
            'state' => $faker->word,
            'country_code' => $faker->countryCode,
        ];
    }
}
