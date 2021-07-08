<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\GoodsOptionPlural;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsOptionPluralFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GoodsOptionPlural::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'goods_id' => $this->faker->numberBetween(1, 1000),
            'option_id' => $this->faker->numberBetween(1, 1000),
            'value_id' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
