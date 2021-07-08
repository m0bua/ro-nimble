<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OptionValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;
        $optionIds = Option::pluck('id')->toArray();

        return [
            'id' => $faker->unique()->numberBetween(),
            'option_id' => $optionIds ? $faker->randomElement($optionIds) : $faker->numberBetween(1, 1000),
            'ext_id' => $faker->word(),
            'name' => $faker->word(),
            'status' => $faker->word(),
            'order' => $faker->randomDigit(),
            'similars_value' => $faker->word(),
            'show_value_in_short_set' => (int)$faker->boolean(),
            'color' => $faker->word(),
            'record_type' => $faker->numberBetween(1, 100),
            'is_section' => (int)$faker->boolean(),
        ];
    }
}
