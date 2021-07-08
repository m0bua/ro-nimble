<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Option;
use App\ValueObjects\Options;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        $types = collect(Options::OPTIONS_BY_TYPES)->flatten()->toArray();

        return [
            'id' => $faker->unique()->numberBetween(1, 1000),
            'name' => $faker->word,
            'type' => $faker->randomElement($types),
            'ext_id' => $faker->word,
            'parent_id' => null,
            'category_id' => $faker->numberBetween(1, 1000),
            'filtering_type' => $faker->word,
            'state' => 'active',
            'for_record_type' => $faker->word,
            'order' => $faker->randomDigit(),
            'record_type' => $faker->randomDigit(),
            'option_record_comparable' => $faker->word,
            'option_record_status' => $faker->word,
            'affect_group_photo' => $faker->boolean ? 't' : 'f',
        ];
    }
}
