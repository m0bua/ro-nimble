<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Series;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Series::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'name' => $this->faker->userName,
            'category_id' => $this->faker->numberBetween(1, 1000),
            'producer_id' => $this->faker->numberBetween(1, 1000),
            'ext_id' => $this->faker->word(),
        ];
    }
}
