<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\PromotionConstructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionConstructorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromotionConstructor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'promotion_id' => $this->faker->numberBetween(1, 1000),
            'gift_id' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
