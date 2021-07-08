<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\CategoryOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'category_id' => $this->faker->numberBetween(1, 1000),
            'option_id' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
